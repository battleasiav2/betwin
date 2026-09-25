<?php
/**
 * One-shot: wipe ALL member/user data (deposits, withdrawals, game logs, tickets…).
 * Keeps: admins, gateways, settings, games catalog, API settings, promotions config.
 *
 * Visit once after Hostinger Git Pull:
 *   https://bet369win.com/clean-user-data.php?token=BET369WIN-CLEAN-USERS-2026
 * Auto-deletes this file when done.
 */
header('Content-Type: text/plain; charset=utf-8');

if (!hash_equals('BET369WIN-CLEAN-USERS-2026', (string) ($_GET['token'] ?? ''))) {
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

// Member / transaction / activity tables only — NEVER admins / settings / gateways / games catalog
$tables = [
    'users',
    'deposits',
    'withdrawals',
    'transactions',
    'game_logs',
    'provider_game_logs',
    'provider_callbacks',
    'game_launch_stats',
    'commission_logs',
    'gameplay_bonus_logs',
    'guess_bonuses',
    'user_logins',
    'user_withdraw_methods',
    'balance_transfers',
    'redeem_logs',
    'support_tickets',
    'support_messages',
    'support_attachments',
    'notification_logs',
    'admin_notifications',
    'password_resets',
    'personal_access_tokens',
    'device_tokens',
    'subscribers',
    'cron_job_logs',
    'bkash_token',
    'update_logs',
];

$existing = [];
$res = $conn->query('SHOW TABLES');
while ($row = $res->fetch_row()) {
    $existing[strtolower($row[0])] = $row[0];
}

$conn->query('SET FOREIGN_KEY_CHECKS=0');
$cleared = 0;
$skipped = 0;
foreach ($tables as $t) {
    $key = strtolower($t);
    if (!isset($existing[$key])) {
        echo "skip_missing={$t}\n";
        $skipped++;
        continue;
    }
    $real = $existing[$key];
    $before = 0;
    $c = $conn->query('SELECT COUNT(*) AS c FROM `' . $conn->real_escape_string($real) . '`');
    if ($c && ($r = $c->fetch_assoc())) {
        $before = (int) $r['c'];
    }
    if (!$conn->query('TRUNCATE TABLE `' . $conn->real_escape_string($real) . '`')) {
        echo "fail={$real} err=" . $conn->error . "\n";
        continue;
    }
    echo "truncated={$real} rows_before={$before}\n";
    $cleared++;
}
$conn->query('SET FOREIGN_KEY_CHECKS=1');

$adminCount = 0;
if (isset($existing['admins'])) {
    $a = $conn->query('SELECT COUNT(*) AS c FROM admins');
    if ($a && ($r = $a->fetch_assoc())) {
        $adminCount = (int) $r['c'];
    }
}
$userCount = 0;
if (isset($existing['users'])) {
    $u = $conn->query('SELECT COUNT(*) AS c FROM users');
    if ($u && ($r = $u->fetch_assoc())) {
        $userCount = (int) $r['c'];
    }
}

echo "tables_cleared={$cleared}\n";
echo "tables_missing={$skipped}\n";
echo "admins_kept={$adminCount}\n";
echo "users_now={$userCount}\n";
echo "kept=admins,gateways,withdraw_methods,general_settings,games,api_*,frontends,pages,promotions,redeem_codes\n";

$conn->close();
@unlink(__FILE__);
echo "done\n";
