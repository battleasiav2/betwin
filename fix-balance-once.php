<?php
/**
 * One-time: https://bet369win.com/fix-balance-once.php?key=bet369win-fix-2026
 * Self-deletes after run.
 */
header('Content-Type: text/plain; charset=utf-8');
if (($_GET['key'] ?? '') !== 'bet369win-fix-2026') {
    http_response_code(403);
    echo "forbidden\n";
    exit;
}

$envPath = __DIR__ . '/core/.env';
$db = ['host' => 'localhost', 'user' => '', 'pass' => '', 'name' => ''];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || ($line[0] ?? '') === '#' || !str_contains($line, '=')) {
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
        'DB_USERNAME' => $db['user'] = $v,
        'DB_PASSWORD' => $db['pass'] = $v,
        'DB_DATABASE' => $db['name'] = $v,
        default => null,
    };
}

$mysqli = @new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
if ($mysqli->connect_error) {
    echo 'DB fail: ' . $mysqli->connect_error . "\n";
    exit(1);
}
$mysqli->set_charset('utf8mb4');

$username = '01911626000';
$balance = 500000.0;

$stmt = $mysqli->prepare('SELECT id, username, balance FROM users WHERE username=? OR mobile=? LIMIT 1');
$stmt->bind_param('ss', $username, $username);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if ($row) {
    $id = (int) $row['id'];
    $upd = $mysqli->prepare('UPDATE users SET balance=?, updated_at=NOW() WHERE id=?');
    $upd->bind_param('di', $balance, $id);
    $upd->execute();
    echo "UPDATED id={$id} username={$row['username']} old={$row['balance']} new={$balance}\n";
} else {
    $email = $username . '@bet369win.com';
    $hash = password_hash('123456', PASSWORD_BCRYPT);
    $ins = $mysqli->prepare("INSERT INTO users (firstname, lastname, username, email, dial_code, country_code, mobile, ref_by, balance, demo_balance, password, status, ev, sv, ts, tv, kv, profile_complete, login_by, created_at, updated_at, turnover_requirement) VALUES ('User', ?, ?, ?, '880', 'BD', ?, 0, ?, 0, ?, 1, 1, 1, 0, 1, 1, 1, 'mobile', NOW(), NOW(), 0)");
    $ins->bind_param('ssssds', $username, $username, $email, $username, $balance, $hash);
    if (!$ins->execute()) {
        echo 'INSERT fail: ' . $ins->error . "\n";
        exit(1);
    }
    echo "CREATED username={$username} balance={$balance} pass=123456\n";
}

@unlink(__FILE__);
echo "done\n";
