<?php
/**
 * One-shot: restore prior RapidVerse production API into api_game_settings + .env
 * Visit: https://bet369win.com/restore-api-settings.php?token=BET369WIN-RESTORE-API-2026
 * Auto-deletes after success.
 */
header('Content-Type: text/plain; charset=utf-8');

if (!hash_equals('BET369WIN-RESTORE-API-2026', (string) ($_GET['token'] ?? ''))) {
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
$rv = [
    'api_url'      => 'https://www.rapidverse.site/api/versev1',
    'api_token'    => '3ad0d33f2bd3e61667fce3600dbbe903a298d1d975585970efb281d39bc16fb5',
    'secret_key'   => '7536eaeabcb058a34ba41b0c61890575445327aa1d2ad919a9735f0c5aeb7fac',
    'callback_url' => 'https://bet369win.com/callback.php',
    'agent_user'   => 'nix626000',
    'api_prefix'   => 'nix6260006107',
    'currency'     => 'BDT',
];

foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
        continue;
    }
    [$k, $v] = explode('=', $line, 2);
    $k = trim($k);
    $v = trim($v);
    if ((str_starts_with($v, '"') && str_ends_with($v, '"')) || (str_starts_with($v, "'") && str_ends_with($v, "'"))) {
        $v = substr($v, 1, -1);
    }
    match ($k) {
        'DB_HOST' => $db['host'] = $v,
        'DB_PORT' => $db['port'] = $v,
        'DB_USERNAME' => $db['user'] = $v,
        'DB_PASSWORD' => $db['pass'] = $v,
        'DB_DATABASE' => $db['name'] = $v,
        'RAPIDVERSE_API_URL' => $rv['api_url'] = $v ?: $rv['api_url'],
        'RAPIDVERSE_API_TOKEN' => $rv['api_token'] = $v ?: $rv['api_token'],
        'RAPIDVERSE_SECRET_KEY' => $rv['secret_key'] = $v ?: $rv['secret_key'],
        'RAPIDVERSE_CALLBACK_URL' => $rv['callback_url'] = $v ?: $rv['callback_url'],
        'RAPIDVERSE_AGENT_USER' => $rv['agent_user'] = $v ?: $rv['agent_user'],
        'RAPIDVERSE_API_PREFIX' => $rv['api_prefix'] = $v ?: $rv['api_prefix'],
        'RAPIDVERSE_CURRENCY' => $rv['currency'] = $v ?: $rv['currency'],
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

$hasTable = $conn->query("SHOW TABLES LIKE 'api_game_settings'");
if (!$hasTable || $hasTable->num_rows === 0) {
    $conn->query("CREATE TABLE api_game_settings (
        id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        api_url VARCHAR(255) NOT NULL,
        api_token TEXT NULL,
        secret_key TEXT NULL,
        callback_url VARCHAR(255) NULL,
        agent_user VARCHAR(100) NULL,
        api_prefix VARCHAR(64) NULL,
        currency VARCHAR(10) DEFAULT 'BDT',
        created_at TIMESTAMP NULL,
        updated_at TIMESTAMP NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "table_created=1\n";
} else {
    $cols = [];
    $cr = $conn->query('SHOW COLUMNS FROM api_game_settings');
    while ($row = $cr->fetch_assoc()) {
        $cols[$row['Field']] = true;
    }
    if (empty($cols['api_prefix'])) {
        $conn->query("ALTER TABLE api_game_settings ADD COLUMN api_prefix VARCHAR(64) NULL AFTER agent_user");
        echo "col_api_prefix=1\n";
    }
}

$esc = static fn (string $s) => "'" . $conn->real_escape_string($s) . "'";
$now = "'" . date('Y-m-d H:i:s') . "'";
$existing = $conn->query('SELECT id FROM api_game_settings ORDER BY id ASC LIMIT 1');
if ($existing && ($row = $existing->fetch_assoc())) {
    $sql = 'UPDATE api_game_settings SET '
        . 'api_url=' . $esc($rv['api_url']) . ','
        . 'api_token=' . $esc($rv['api_token']) . ','
        . 'secret_key=' . $esc($rv['secret_key']) . ','
        . 'callback_url=' . $esc($rv['callback_url']) . ','
        . 'agent_user=' . $esc($rv['agent_user']) . ','
        . 'api_prefix=' . $esc($rv['api_prefix']) . ','
        . 'currency=' . $esc($rv['currency']) . ','
        . 'updated_at=' . $now
        . ' WHERE id=' . (int) $row['id'];
    $conn->query($sql);
    echo "db_updated=1 id=" . $row['id'] . "\n";
} else {
    $sql = 'INSERT INTO api_game_settings (api_url, api_token, secret_key, callback_url, agent_user, api_prefix, currency, created_at, updated_at) VALUES ('
        . $esc($rv['api_url']) . ','
        . $esc($rv['api_token']) . ','
        . $esc($rv['secret_key']) . ','
        . $esc($rv['callback_url']) . ','
        . $esc($rv['agent_user']) . ','
        . $esc($rv['api_prefix']) . ','
        . $esc($rv['currency']) . ','
        . $now . ',' . $now . ')';
    $conn->query($sql);
    echo "db_inserted=1\n";
}

$check = $conn->query('SELECT api_url, LEFT(api_token,8) AS tok, LEFT(secret_key,8) AS sec, agent_user, api_prefix, currency FROM api_game_settings ORDER BY id ASC LIMIT 1');
$c = $check ? $check->fetch_assoc() : null;
echo 'api_url=' . ($c['api_url'] ?? '') . "\n";
echo 'token_prefix=' . ($c['tok'] ?? '') . "…\n";
echo 'secret_prefix=' . ($c['sec'] ?? '') . "…\n";
echo 'agent=' . ($c['agent_user'] ?? '') . "\n";
echo 'prefix=' . ($c['api_prefix'] ?? '') . "\n";
echo 'currency=' . ($c['currency'] ?? '') . "\n";

if (is_writable($envPath)) {
    $content = file_get_contents($envPath);
    $pairs = [
        'RAPIDVERSE_API_URL' => $rv['api_url'],
        'RAPIDVERSE_API_TOKEN' => $rv['api_token'],
        'RAPIDVERSE_SECRET_KEY' => $rv['secret_key'],
        'RAPIDVERSE_CALLBACK_URL' => $rv['callback_url'],
        'RAPIDVERSE_AGENT_USER' => $rv['agent_user'],
        'RAPIDVERSE_API_PREFIX' => $rv['api_prefix'],
        'RAPIDVERSE_CURRENCY' => $rv['currency'],
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
}

$conn->close();
@unlink(__FILE__);
echo "done\n";
