<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

$envPath = __DIR__ . '/core/.env';
$db = [
    'host' => 'localhost',
    'user' => 'u811189100_betwin',
    'pass' => '',
    'name' => 'u811189100_betwin',
];

if (is_readable($envPath)) {
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
        if ($key === 'DB_HOST') {
            $db['host'] = $value;
        } elseif ($key === 'DB_USERNAME') {
            $db['user'] = $value;
        } elseif ($key === 'DB_PASSWORD') {
            $db['pass'] = $value;
        } elseif ($key === 'DB_DATABASE') {
            $db['name'] = $value;
        }
    }
}

define('DB_HOST', $db['host']);
define('DB_USER', $db['user']);
define('DB_PASS', $db['pass']);
define('DB_NAME', $db['name']);

$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    echo json_encode(['code' => 1]);
    exit;
}

$userId   = (int) $data['user_id'];
$gameId   = 0;
$gameName = !empty($data['game_code']) ? trim($data['game_code']) : 'API Game';
$bet      = (float) $data['bet_amount'];
$win      = (float) $data['win_amount'];
$serial   = trim($data['serial_number']);
$winStat  = $win > $bet ? 1 : 0;

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    echo json_encode(['code' => 1, 'msg' => 'db error']);
    exit;
}
$conn->set_charset('utf8mb4');

$chk = $conn->prepare('SELECT id FROM game_logs WHERE serial_number=?');
$chk->bind_param('s', $serial);
$chk->execute();
if ($chk->get_result()->num_rows) {
    echo json_encode(['code' => 0]);
    exit;
}

$q = $conn->prepare('SELECT balance, turnover_requirement FROM users WHERE id=?');
$q->bind_param('i', $userId);
$q->execute();
$userData = $q->get_result()->fetch_assoc();
if (!$userData) {
    echo json_encode(['code' => 1, 'msg' => 'user not found']);
    exit;
}

$bal = (float) $userData['balance'];
$turnover_req = (float) $userData['turnover_requirement'];
$newBal = $bal - $bet + $win;

if ($turnover_req > 0 && $bet > 0) {
    $new_turnover = $turnover_req - $bet;
    if ($new_turnover < 0) {
        $new_turnover = 0;
    }
} else {
    $new_turnover = $turnover_req;
}

$u = $conn->prepare('UPDATE users SET balance=?, turnover_requirement=? WHERE id=?');
$u->bind_param('ddi', $newBal, $new_turnover, $userId);
$u->execute();

$l = $conn->prepare('INSERT INTO game_logs (user_id, game_id, game_name, invest, win_amo, serial_number, win_status, demo_play, status, created_at) VALUES (?,?,?,?,?,?,?,0,1,NOW())');
$l->bind_param('iisddsi', $userId, $gameId, $gameName, $bet, $win, $serial, $winStat);
$l->execute();

echo json_encode(['code' => 0, 'balance' => $newBal]);
