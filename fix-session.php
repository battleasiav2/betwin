<?php
/**
 * One-shot: long session + database sessions (stops random logout).
 * Visit after Git Pull:
 *   https://bet369win.com/fix-session.php?token=BET369WIN-FIX-SESSION-2026
 */
header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store');

if (!hash_equals('BET369WIN-FIX-SESSION-2026', (string) ($_GET['token'] ?? ''))) {
    http_response_code(403);
    echo "forbidden\n";
    exit;
}

$envPath = __DIR__ . '/core/.env';
if (!is_file($envPath)) {
    http_response_code(500);
    echo "missing_env\n";
    exit;
}

$db = ['host' => 'localhost', 'port' => '3306', 'user' => '', 'pass' => '', 'name' => ''];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
    [$k, $v] = explode('=', $line, 2);
    $k = trim($k); $v = trim($v);
    if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
        $v = substr($v, 1, -1);
    }
    match ($k) {
        'DB_HOST' => $db['host'] = $v,
        'DB_PORT' => $db['port'] = $v,
        'DB_USERNAME' => $db['user'] = $v,
        'DB_PASSWORD' => $db['pass'] = $v,
        'DB_DATABASE' => $db['name'] = $v,
        default => null,
    };
}

$conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name'], (int) $db['port']);
if ($conn->connect_error) {
    http_response_code(500);
    echo 'db_error=' . $conn->connect_error . "\n";
    exit;
}
$conn->set_charset('utf8mb4');

$has = $conn->query("SHOW TABLES LIKE 'sessions'");
if (!$has || $has->num_rows === 0) {
    $ok = $conn->query("CREATE TABLE sessions (
        id VARCHAR(255) NOT NULL PRIMARY KEY,
        user_id BIGINT UNSIGNED NULL,
        ip_address VARCHAR(45) NULL,
        user_agent TEXT NULL,
        payload LONGTEXT NOT NULL,
        last_activity INT NOT NULL,
        INDEX sessions_user_id_index (user_id),
        INDEX sessions_last_activity_index (last_activity)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo $ok ? "sessions_table=created\n" : ('sessions_table_fail=' . $conn->error . "\n");
} else {
    echo "sessions_table=exists\n";
}
$conn->close();

$content = file_get_contents($envPath);
$pairs = [
    'SESSION_DRIVER' => 'database',
    'SESSION_LIFETIME' => '43200',
    'SESSION_EXPIRE_ON_CLOSE' => 'false',
    'SESSION_SECURE_COOKIE' => 'true',
    'SESSION_SAME_SITE' => 'lax',
    'SESSION_HTTP_ONLY' => 'true',
];
foreach ($pairs as $key => $val) {
    if (preg_match('/^' . preg_quote($key, '/') . '=/m', $content)) {
        $content = preg_replace('/^' . preg_quote($key, '/') . '=.*$/m', $key . '=' . $val, $content);
    } else {
        $content = rtrim($content) . "\n{$key}={$val}\n";
    }
}
file_put_contents($envPath, $content);
echo "env_updated=1\n";

foreach (['config', 'cache', 'views', 'routes'] as $type) {
    $dir = __DIR__ . '/core/bootstrap/cache';
    if ($type === 'config' && is_file($dir . '/config.php')) {
        @unlink($dir . '/config.php');
        echo "cleared=config.php\n";
    }
}

// Clear compiled config via artisan if available
$artisan = __DIR__ . '/core/artisan';
if (is_file($artisan)) {
    $php = PHP_BINARY ?: 'php';
    @exec(escapeshellarg($php) . ' ' . escapeshellarg($artisan) . ' config:clear 2>&1', $out1);
    @exec(escapeshellarg($php) . ' ' . escapeshellarg($artisan) . ' cache:clear 2>&1', $out2);
    echo "artisan_config_clear=1\n";
}

@unlink(__FILE__);
echo "lifetime_minutes=43200 (30 days)\n";
echo "driver=database\n";
echo "done\n";
echo "NOTE: login again once after this fix.\n";
