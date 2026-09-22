<?php
/**
 * One-time Hostinger setup — creates core/.env (bypasses Laravel).
 * Open: https://bet369win.com/setup-env.php
 * Delete this file after the site works.
 */
declare(strict_types=1);

header('Cache-Control: no-store');

$root = __DIR__;
$envPath = $root . '/core/.env';
$examplePath = $root . '/core/env.hostinger.example';
$done = is_file($envPath) && trim((string) file_get_contents($envPath)) !== '';

$defaultPass = '';
$localEnv = $root . '/core/.env';
// Prefer password already typed in the form / POST only — never hardcode here.

$error = '';
$ok = '';

if ($done && ($_GET['force'] ?? '') !== '1') {
    header('Location: /');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dbPass = (string) ($_POST['db_password'] ?? '');
    $appUrl = rtrim((string) ($_POST['app_url'] ?? 'https://bet369win.com'), '/');
    $token = trim((string) ($_POST['api_token'] ?? ''));
    $secret = trim((string) ($_POST['secret_key'] ?? ''));

    if ($dbPass === '') {
        $error = 'DB password required (Hostinger → Databases → MySQL).';
    } elseif (!is_readable($examplePath)) {
        $error = 'Missing core/env.hostinger.example — redeploy/pull latest git first.';
    } else {
        $tpl = file_get_contents($examplePath);
        $tpl = preg_replace('/^APP_URL=.*/m', 'APP_URL=' . $appUrl, $tpl);
        $tpl = preg_replace('/^DB_HOST=.*/m', 'DB_HOST=localhost', $tpl);
        $tpl = preg_replace('/^DB_PASSWORD=.*/m', 'DB_PASSWORD=' . $dbPass, $tpl);
        if ($token !== '') {
            if (preg_match('/^RAPIDVERSE_API_TOKEN=.*/m', $tpl)) {
                $tpl = preg_replace('/^RAPIDVERSE_API_TOKEN=.*/m', 'RAPIDVERSE_API_TOKEN=' . $token, $tpl);
            } else {
                $tpl .= "\nRAPIDVERSE_API_TOKEN=" . $token . "\n";
            }
        }
        if ($secret !== '') {
            if (preg_match('/^RAPIDVERSE_SECRET_KEY=.*/m', $tpl)) {
                $tpl = preg_replace('/^RAPIDVERSE_SECRET_KEY=.*/m', 'RAPIDVERSE_SECRET_KEY=' . $secret, $tpl);
            } else {
                $tpl .= "\nRAPIDVERSE_SECRET_KEY=" . $secret . "\n";
            }
        }

        // Quick DB check
        mysqli_report(MYSQLI_REPORT_OFF);
        $mysqli = @new mysqli('localhost', 'u811189100_betwin', $dbPass, 'u811189100_betwin');
        if ($mysqli->connect_error) {
            $error = 'DB connect failed: ' . $mysqli->connect_error . ' — password wrong?';
        } else {
            $mysqli->close();
            if (@file_put_contents($envPath, $tpl) === false) {
                $error = 'Cannot write core/.env — check File Manager permissions on core/ folder.';
            } else {
                @chmod($envPath, 0600);
                // clear laravel caches if present
                foreach ([
                    $root . '/core/bootstrap/cache/config.php',
                    $root . '/core/bootstrap/cache/packages.php',
                    $root . '/core/bootstrap/cache/services.php',
                ] as $c) {
                    if (is_file($c)) {
                        @unlink($c);
                    }
                }
                $ok = 'core/.env created. Opening site…';
                header('Refresh: 2; url=/');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="bn">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>BET369WIN — Create .env</title>
  <style>
    body{font-family:Arial,sans-serif;background:#e8f0fa;color:#172033;margin:0;padding:24px}
    .box{max-width:520px;margin:40px auto;background:#fff;border:1px solid #d5e4f7;border-radius:16px;padding:28px}
    h1{color:#123b66;font-size:20px;margin:0 0 8px}
    p{color:#6b7280;line-height:1.5}
    label{display:block;font-size:13px;margin:14px 0 6px;color:#123b66;font-weight:700}
    input{width:100%;box-sizing:border-box;padding:12px;border:1px solid #c9d9ef;border-radius:10px;font-size:15px}
    button{margin-top:18px;width:100%;padding:14px;border:0;border-radius:10px;background:#123b66;color:#fff;font-weight:700;font-size:15px;cursor:pointer}
    .err{background:#fee2e2;color:#991b1b;padding:10px 12px;border-radius:8px;margin:12px 0}
    .ok{background:#dbeafe;color:#1e40af;padding:10px 12px;border-radius:8px;margin:12px 0}
    code{background:#e8f0fa;padding:2px 6px;border-radius:4px}
  </style>
</head>
<body>
  <div class="box">
    <h1>Site setup — create core/.env</h1>
    <p>Redeploy <strong>`.env` create kore na</strong>. Ekbar DB password dile domain chalube.</p>
    <?php if ($error): ?><div class="err"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($ok): ?><div class="ok"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
    <form method="post" autocomplete="off">
      <label>APP URL</label>
      <input name="app_url" value="<?= htmlspecialchars($_POST['app_url'] ?? 'https://bet369win.com') ?>" required>
      <label>MySQL password (Hostinger → Databases)</label>
      <input name="db_password" type="password" value="<?= htmlspecialchars($_POST['db_password'] ?? '') ?>" required placeholder="u811189100_betwin password">
      <label>RapidVerse API Token (optional)</label>
      <input name="api_token" value="<?= htmlspecialchars($_POST['api_token'] ?? '') ?>">
      <label>RapidVerse Secret Key (optional)</label>
      <input name="secret_key" value="<?= htmlspecialchars($_POST['secret_key'] ?? '') ?>">
      <button type="submit">Create .env &amp; start site</button>
    </form>
    <p style="margin-top:18px;font-size:13px">DB: <code>u811189100_betwin</code> · User: <code>u811189100_betwin</code> · Host: <code>localhost</code></p>
  </div>
</body>
</html>
