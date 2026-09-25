<?php
/**
 * Sync api_game_controls rows from RapidVerse provider map.
 * Visit once after Git Pull:
 *   https://bet369win.com/sync-providers.php?token=BET369WIN-SYNC-PROVIDERS-2026
 */
header('Content-Type: text/plain; charset=utf-8');
if (!hash_equals('BET369WIN-SYNC-PROVIDERS-2026', (string) ($_GET['token'] ?? ''))) {
    http_response_code(403);
    echo "forbidden\n";
    exit;
}

$envPath = __DIR__ . '/core/.env';
$providersFile = __DIR__ . '/core/config/rapidverse_providers.php';
if (!is_file($envPath) || !is_file($providersFile)) {
    http_response_code(500);
    echo "missing_files\n";
    exit;
}

$providers = require $providersFile;
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

$has = $conn->query("SHOW TABLES LIKE 'api_game_controls'");
if (!$has || $has->num_rows === 0) {
    $conn->query("CREATE TABLE api_game_controls (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        slug VARCHAR(64) NOT NULL UNIQUE,
        status TINYINT NOT NULL DEFAULT 1,
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "table_created=1\n";
}

$ins = 0; $upd = 0;
$now = date('Y-m-d H:i:s');
foreach ($providers as $p) {
    $slug = (string) ($p['slug'] ?? '');
    $name = (string) ($p['name'] ?? $slug);
    if ($slug === '') continue;
    $slugEsc = $conn->real_escape_string($slug);
    $nameEsc = $conn->real_escape_string($name);
    $exists = $conn->query("SELECT id FROM api_game_controls WHERE slug='{$slugEsc}' LIMIT 1");
    if ($exists && ($row = $exists->fetch_assoc())) {
        $conn->query("UPDATE api_game_controls SET name='{$nameEsc}', updated_at='{$now}' WHERE id=" . (int) $row['id']);
        $upd++;
    } else {
        $conn->query("INSERT INTO api_game_controls (name, slug, status, created_at, updated_at) VALUES ('{$nameEsc}','{$slugEsc}',1,'{$now}','{$now}')");
        $ins++;
    }
}

$count = 0;
$c = $conn->query('SELECT COUNT(*) AS c FROM api_game_controls');
if ($c && ($r = $c->fetch_assoc())) $count = (int) $r['c'];

echo "inserted={$ins}\n";
echo "updated={$upd}\n";
echo "total_controls={$count}\n";
echo "providers_config=" . count($providers) . "\n";
$conn->close();
@unlink(__FILE__);
echo "done\n";
