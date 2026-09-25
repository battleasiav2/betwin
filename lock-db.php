<?php
/**
 * Lock Hostinger DB/.env so Git pull + repair scripts cannot overwrite it again.
 * Run ONCE on Hostinger after you set the correct DB:
 *   https://bet369win.com/lock-db.php?token=BET369WIN-LOCK-DB-2026
 */
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

if (!hash_equals('BET369WIN-LOCK-DB-2026', (string) ($_GET['token'] ?? ''))) {
    http_response_code(403);
    echo "forbidden\n";
    exit;
}

$envPath = __DIR__ . '/core/.env';
$lockPath = __DIR__ . '/core/.env.locked';
$bakPath = __DIR__ . '/core/.env.server.bak';

if (!is_file($envPath)) {
    http_response_code(500);
    echo "missing_env — create core/.env first with correct DB, then run this again.\n";
    exit;
}

$env = file_get_contents($envPath);
if ($env === false || trim($env) === '') {
    http_response_code(500);
    echo "empty_env\n";
    exit;
}

// Permanent server backup (never in git)
if (@file_put_contents($bakPath, $env) === false) {
    echo "warn: could not write .env.server.bak\n";
} else {
    @chmod($bakPath, 0600);
    echo "backup=core/.env.server.bak\n";
}

// Mark locked
@file_put_contents($lockPath, "LOCKED " . date('c') . "\nDo not overwrite core/.env from git or fix scripts.\n");
echo "lock=core/.env.locked\n";

// Ensure ENV_LOCKED=1 inside .env
if (preg_match('/^ENV_LOCKED=/m', $env)) {
    $env = preg_replace('/^ENV_LOCKED=.*/m', 'ENV_LOCKED=1', $env);
} else {
    $env = rtrim($env) . "\nENV_LOCKED=1\n";
}
file_put_contents($envPath, $env);
@chmod($envPath, 0600);

// Show current DB (no password)
foreach (explode("\n", $env) as $line) {
    $line = trim($line);
    if (str_starts_with($line, 'DB_DATABASE=') || str_starts_with($line, 'DB_USERNAME=') || str_starts_with($line, 'DB_HOST=')) {
        echo $line . "\n";
    }
    if (str_starts_with($line, 'DB_PASSWORD=')) {
        echo "DB_PASSWORD=(set, hidden)\n";
    }
}

echo "done — DB settings are now locked.\n";
echo "Git Pull will no longer replace core/.env (removed from repo).\n";
@unlink(__FILE__);
