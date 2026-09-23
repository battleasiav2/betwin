<?php
/**
 * RapidVerse game settlement callback — authenticated + transactional.
 * Requires matching X-Secret-Key (or X-API-Token) from provider.
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['code' => 1, 'msg' => 'method not allowed']);
    exit;
}

$envPath = __DIR__ . '/core/.env';
$db = [
    'host' => 'localhost',
    'user' => 'u811189100_betwin',
    'pass' => '',
    'name' => 'u811189100_betwin',
];
$secretKey = '';
$apiToken = '';

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
        match ($key) {
            'DB_HOST' => $db['host'] = $value,
            'DB_USERNAME' => $db['user'] = $value,
            'DB_PASSWORD' => $db['pass'] = $value,
            'DB_DATABASE' => $db['name'] = $value,
            'RAPIDVERSE_SECRET_KEY' => $secretKey = $value,
            'RAPIDVERSE_API_TOKEN' => $apiToken = $value,
            default => null,
        };
    }
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: 'null', true);
if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['code' => 1, 'msg' => 'invalid json']);
    exit;
}

$conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['code' => 1, 'msg' => 'db error']);
    exit;
}
$conn->set_charset('utf8mb4');

// Prefer DB settings over .env when table exists
$tbl = $conn->query("SHOW TABLES LIKE 'api_game_settings'");
if ($tbl && $tbl->num_rows > 0) {
    $row = $conn->query('SELECT secret_key, api_token FROM api_game_settings ORDER BY id ASC LIMIT 1');
    if ($row && ($s = $row->fetch_assoc())) {
        if (!empty($s['secret_key'])) {
            $secretKey = $s['secret_key'];
        }
        if (!empty($s['api_token'])) {
            $apiToken = $s['api_token'];
        }
    }
}

if ($secretKey === '' && $apiToken === '') {
    http_response_code(503);
    echo json_encode(['code' => 1, 'msg' => 'callback auth not configured']);
    exit;
}

$headers = function_exists('getallheaders') ? getallheaders() : [];
$headerMap = [];
foreach ($headers as $hk => $hv) {
    $headerMap[strtolower($hk)] = trim((string) $hv);
}

$providedSecret = $headerMap['x-secret-key']
    ?? $headerMap['x-api-secret']
    ?? ($data['secret_key'] ?? '');
$providedToken = $headerMap['x-api-token']
    ?? $headerMap['authorization']
    ?? ($data['api_token'] ?? '');
if (str_starts_with(strtolower((string) $providedToken), 'bearer ')) {
    $providedToken = trim(substr($providedToken, 7));
}

$sigHeader = $headerMap['x-signature']
    ?? $headerMap['x-r4nkt-signature']
    ?? $headerMap['signature']
    ?? '';

$authOk = false;
if ($secretKey !== '' && $providedSecret !== '' && hash_equals($secretKey, (string) $providedSecret)) {
    $authOk = true;
}
if (!$authOk && $apiToken !== '' && $providedToken !== '' && hash_equals($apiToken, (string) $providedToken)) {
    $authOk = true;
}
if (!$authOk && $secretKey !== '' && $sigHeader !== '') {
    $expected = hash_hmac('sha256', $raw, $secretKey);
    if (hash_equals($expected, strtolower($sigHeader)) || hash_equals($expected, $sigHeader)) {
        $authOk = true;
    }
}

if (!$authOk) {
    http_response_code(401);
    echo json_encode(['code' => 1, 'msg' => 'unauthorized']);
    exit;
}

$userId   = (int) ($data['user_id'] ?? 0);
$gameName = !empty($data['game_code']) ? substr(trim((string) $data['game_code']), 0, 120) : 'API Game';
$bet      = (float) ($data['bet_amount'] ?? 0);
$win      = (float) ($data['win_amount'] ?? 0);
$serial   = trim((string) ($data['serial_number'] ?? ''));
$gameId   = 0;
$winStat  = $win > $bet ? 1 : 0;

if ($userId <= 0 || $serial === '' || strlen($serial) > 190) {
    http_response_code(400);
    echo json_encode(['code' => 1, 'msg' => 'invalid payload']);
    exit;
}
if ($bet < 0 || $win < 0 || $bet > 10000000 || $win > 10000000) {
    http_response_code(400);
    echo json_encode(['code' => 1, 'msg' => 'invalid amounts']);
    exit;
}

$conn->begin_transaction();
try {
    $chk = $conn->prepare('SELECT id FROM game_logs WHERE serial_number=? LIMIT 1 FOR UPDATE');
    $chk->bind_param('s', $serial);
    $chk->execute();
    if ($chk->get_result()->num_rows) {
        $conn->commit();
        echo json_encode(['code' => 0, 'msg' => 'duplicate']);
        exit;
    }

    $q = $conn->prepare('SELECT balance, turnover_requirement FROM users WHERE id=? LIMIT 1 FOR UPDATE');
    $q->bind_param('i', $userId);
    $q->execute();
    $userData = $q->get_result()->fetch_assoc();
    if (!$userData) {
        $conn->rollback();
        http_response_code(404);
        echo json_encode(['code' => 1, 'msg' => 'user not found']);
        exit;
    }

    $bal = (float) $userData['balance'];
    $turnover_req = (float) $userData['turnover_requirement'];

    // Do not allow balance to go below zero from bet
    if ($bet > $bal + 0.0001) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(['code' => 1, 'msg' => 'insufficient balance', 'balance' => $bal]);
        exit;
    }

    $newBal = round($bal - $bet + $win, 2);
    if ($newBal < 0) {
        $newBal = 0.0;
    }

    if ($turnover_req > 0 && $bet > 0) {
        $new_turnover = max(0, $turnover_req - $bet);
    } else {
        $new_turnover = $turnover_req;
    }

    $u = $conn->prepare('UPDATE users SET balance=?, turnover_requirement=? WHERE id=?');
    $u->bind_param('ddi', $newBal, $new_turnover, $userId);
    $u->execute();

    $l = $conn->prepare('INSERT INTO game_logs (user_id, game_id, game_name, invest, win_amo, serial_number, win_status, demo_play, status, created_at) VALUES (?,?,?,?,?,?,?,0,1,NOW())');
    $l->bind_param('iisddsi', $userId, $gameId, $gameName, $bet, $win, $serial, $winStat);
    $l->execute();

    $conn->commit();
    echo json_encode(['code' => 0, 'balance' => $newBal]);
} catch (Throwable $e) {
    $conn->rollback();
    http_response_code(500);
    echo json_encode(['code' => 1, 'msg' => 'server error']);
}
