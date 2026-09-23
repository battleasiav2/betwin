<?php
/**
 * One-time live rebrand + admin reset for BET369WIN.
 * Usage: /rebrand-live.php?token=BET369WIN-REBRAND-2026
 * Deletes itself after success.
 */
declare(strict_types=1);

header('Content-Type: text/plain; charset=utf-8');

$token = (string) ($_GET['token'] ?? '');
if (!hash_equals('BET369WIN-REBRAND-2026', $token)) {
    http_response_code(403);
    echo "forbidden\n";
    exit;
}

function brand_replace(string $s): string
{
    $map = [
        'CK7171 Super Admin' => 'BET369WIN Super Admin',
        'support@xaxino.com' => 'support@bet369win.com',
        'CK7171' => 'BET369WIN',
        'ck7171' => 'bet369win',
        'Redjili' => 'BET369WIN',
        'redjili' => 'bet369win',
        'REDJILI' => 'BET369WIN',
        'akashwebd' => 'bet369win',
        'Akashwebd' => 'BET369WIN',
        'Xaxino' => 'BET369WIN',
        'xaxino' => 'bet369win',
        'XAXINO' => 'BET369WIN',
        'WINBUZZ' => 'BET369WIN',
        'Winbuzz' => 'BET369WIN',
        'winbuzz' => 'bet369win',
        'UC777' => 'BET369WIN',
        'uc777' => 'bet369win',
    ];
    return str_replace(array_keys($map), array_values($map), $s);
}

$envPath = __DIR__ . '/core/.env';
if (!is_readable($envPath)) {
    http_response_code(500);
    echo "missing .env\n";
    exit;
}

$db = ['host' => 'localhost', 'port' => '3306', 'user' => '', 'pass' => '', 'name' => ''];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
        continue;
    }
    [$key, $value] = explode('=', $line, 2);
    $key = trim($key);
    $value = trim($value);
    if (
        (str_starts_with($value, '"') && str_ends_with($value, '"')) ||
        (str_starts_with($value, "'") && str_ends_with($value, "'"))
    ) {
        $value = substr($value, 1, -1);
    }
    match ($key) {
        'DB_HOST' => $db['host'] = $value,
        'DB_PORT' => $db['port'] = $value,
        'DB_USERNAME' => $db['user'] = $value,
        'DB_PASSWORD' => $db['pass'] = $value,
        'DB_DATABASE' => $db['name'] = $value,
        default => null,
    };
}

mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name'], (int) $db['port']);
if ($conn->connect_error) {
    http_response_code(500);
    echo "db error\n";
    exit;
}
$conn->set_charset('utf8mb4');

$hash = password_hash('bet369win@7777', PASSWORD_BCRYPT, ['cost' => 12]);
$stmt = $conn->prepare('UPDATE admins SET username=?, password=?, name=?, email=?, updated_at=NOW() WHERE id=1');
$user = 'bet369win';
$name = 'BET369WIN Super Admin';
$email = 'support@bet369win.com';
$stmt->bind_param('ssss', $user, $hash, $name, $email);
$stmt->execute();
echo "admin_updated=" . $stmt->affected_rows . "\n";

$gs = $conn->query('SELECT * FROM general_settings ORDER BY id ASC LIMIT 1');
if ($gs && ($row = $gs->fetch_assoc())) {
    $sets = [];
    $types = '';
    $vals = [];
    foreach ($row as $k => $v) {
        if ($k === 'id' || !is_string($v)) {
            continue;
        }
        $n = brand_replace($v);
        if ($k === 'site_name') {
            $n = 'BET369WIN';
        }
        if ($k === 'email_from_name') {
            $n = 'BET369WIN';
        }
        if ($k === 'email_from') {
            $n = 'support@bet369win.com';
        }
        if ($k === 'sms_from') {
            $n = 'BET369WIN';
        }
        if ($n !== $v) {
            $sets[] = "`$k`=?";
            $types .= 's';
            $vals[] = $n;
        }
    }
    if ($sets) {
        $sql = 'UPDATE general_settings SET ' . implode(',', $sets) . ' WHERE id=?';
        $types .= 'i';
        $vals[] = (int) $row['id'];
        $st = $conn->prepare($sql);
        $st->bind_param($types, ...$vals);
        $st->execute();
        echo "general_settings_fields=" . count($sets) . "\n";
    }
}

$tables = [
    'frontends' => ['data_values', 'seo_content', 'title', 'slug'],
    'pages' => ['name', 'title', 'seo_content', 'description'],
    'extensions' => ['name', 'description', 'script', 'shortcode'],
    'notification_templates' => null,
    'email_sms_templates' => null,
];

foreach ($tables as $table => $cols) {
    $chk = $conn->query("SHOW TABLES LIKE '" . $conn->real_escape_string($table) . "'");
    if (!$chk || $chk->num_rows === 0) {
        continue;
    }
    $res = $conn->query("SELECT * FROM `$table`");
    if (!$res) {
        continue;
    }
    $updated = 0;
    while ($r = $res->fetch_assoc()) {
        $payload = [];
        $useCols = $cols ?? array_keys($r);
        foreach ($useCols as $col) {
            if ($col === 'id' || !isset($r[$col]) || !is_string($r[$col])) {
                continue;
            }
            $n = brand_replace($r[$col]);
            if ($n !== $r[$col]) {
                $payload[$col] = $n;
            }
        }
        if (!$payload) {
            continue;
        }
        $sets = [];
        $types = '';
        $vals = [];
        foreach ($payload as $k => $v) {
            $sets[] = "`$k`=?";
            $types .= 's';
            $vals[] = $v;
        }
        $types .= 'i';
        $vals[] = (int) $r['id'];
        $st = $conn->prepare('UPDATE `' . $table . '` SET ' . implode(',', $sets) . ' WHERE id=?');
        $st->bind_param($types, ...$vals);
        $st->execute();
        $updated++;
    }
    echo "{$table}_rows=$updated\n";
}

$conn->close();
echo "site=BET369WIN\n";
echo "admin_user=bet369win\n";
echo "done\n";

@unlink(__FILE__);
echo "self_deleted=1\n";
