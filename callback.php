<?php
/**
 * RapidVerse seamless wallet callback.
 * Accepts snake_case + camelCase payloads; supports balance/bet/win/settle.
 */
error_reporting(E_ALL);
ini_set('display_errors', 0);
header('Content-Type: application/json');

function cb_log(string $msg, $ctx = null): void
{
    $dir = __DIR__ . '/core/storage/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $line = date('Y-m-d H:i:s') . ' ' . $msg;
    if ($ctx !== null) {
        $line .= ' ' . (is_string($ctx) ? $ctx : json_encode($ctx, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
    @file_put_contents($dir . '/rapidverse_callback.log', $line . "\n", FILE_APPEND);
}

function cb_val(array $data, array $keys, $default = null)
{
    foreach ($keys as $k) {
        if (array_key_exists($k, $data) && $data[$k] !== '' && $data[$k] !== null) {
            return $data[$k];
        }
    }
    return $default;
}

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
    cb_log('invalid_json', $raw);
    http_response_code(400);
    echo json_encode(['code' => 1, 'msg' => 'invalid json']);
    exit;
}

$conn = new mysqli($db['host'], $db['user'], $db['pass'], $db['name']);
if ($conn->connect_error) {
    cb_log('db_error', $conn->connect_error);
    http_response_code(500);
    echo json_encode(['code' => 1, 'msg' => 'db error']);
    exit;
}
$conn->set_charset('utf8mb4');

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
    $headerMap[strtolower((string) $hk)] = trim((string) $hv);
}

$providedSecret = $headerMap['x-secret-key']
    ?? $headerMap['x-api-secret']
    ?? cb_val($data, ['secret_key', 'secretKey', 'secret'], '');
$providedToken = $headerMap['x-api-token']
    ?? $headerMap['authorization']
    ?? cb_val($data, ['api_token', 'apiToken', 'token'], '');
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
    cb_log('unauthorized', ['headers' => array_keys($headerMap), 'body' => $data]);
    http_response_code(401);
    echo json_encode(['code' => 1, 'msg' => 'unauthorized']);
    exit;
}

$userId = (int) cb_val($data, ['user_id', 'userId', 'member_id', 'memberId', 'uid', 'player_id', 'playerId'], 0);
$gameName = (string) cb_val($data, ['game_code', 'gameCode', 'game_id', 'gameId', 'game_name', 'gameName'], 'API Game');
$gameName = substr(trim($gameName), 0, 120) ?: 'API Game';
$serial = trim((string) cb_val($data, [
    'serial_number', 'serialNumber', 'transaction_id', 'transactionId', 'txn_id', 'txnId',
    'bet_id', 'betId', 'round_id', 'roundId', 'id',
], ''));
$action = strtolower((string) cb_val($data, ['action', 'type', 'event', 'method', 'cmd'], 'settle'));

$bet = (float) cb_val($data, ['bet_amount', 'betAmount', 'bet', 'stake', 'debit_amount', 'debitAmount'], 0);
$win = (float) cb_val($data, ['win_amount', 'winAmount', 'win', 'payout', 'credit_amount', 'creditAmount'], 0);
$amount = (float) cb_val($data, ['amount', 'money', 'value'], 0);

// Map action-based amount into bet/win
if ($amount != 0.0 && $bet == 0.0 && $win == 0.0) {
    if (in_array($action, ['bet', 'debit', 'withdraw', 'stake', 'wager'], true)) {
        $bet = abs($amount);
    } elseif (in_array($action, ['win', 'credit', 'deposit', 'payout', 'prize'], true)) {
        $win = abs($amount);
    } elseif (in_array($action, ['refund', 'cancel', 'rollback'], true)) {
        $win = abs($amount);
    } elseif ($amount < 0) {
        $bet = abs($amount);
    } else {
        $win = abs($amount);
    }
}

$gameId = 0;
$winStat = $win > $bet ? 1 : 0;

if ($userId <= 0) {
    cb_log('invalid_user', $data);
    http_response_code(400);
    echo json_encode(['code' => 1, 'msg' => 'invalid user']);
    exit;
}

// Balance inquiry only
if (in_array($action, ['balance', 'getbalance', 'get_balance', 'query'], true)) {
    $q = $conn->prepare('SELECT balance FROM users WHERE id=? LIMIT 1');
    $q->bind_param('i', $userId);
    $q->execute();
    $userData = $q->get_result()->fetch_assoc();
    if (!$userData) {
        http_response_code(404);
        echo json_encode(['code' => 1, 'msg' => 'user not found']);
        exit;
    }
    $bal = round((float) $userData['balance'], 2);
    echo json_encode(['code' => 0, 'balance' => $bal, 'userBalance' => $bal]);
    exit;
}

if ($serial === '' || strlen($serial) > 190) {
    // Allow pure balance push with unique serial fallback for credit-only sync
    if ($win > 0 || $bet > 0) {
        $serial = 'auto-' . $userId . '-' . md5($raw . microtime(true));
        $serial = substr($serial, 0, 190);
    } else {
        cb_log('invalid_serial', $data);
        http_response_code(400);
        echo json_encode(['code' => 1, 'msg' => 'invalid payload']);
        exit;
    }
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
        $q = $conn->prepare('SELECT balance FROM users WHERE id=? LIMIT 1');
        $q->bind_param('i', $userId);
        $q->execute();
        $bal = round((float) ($q->get_result()->fetch_assoc()['balance'] ?? 0), 2);
        $conn->commit();
        echo json_encode(['code' => 0, 'msg' => 'duplicate', 'balance' => $bal, 'userBalance' => $bal]);
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

    if ($bet > $bal + 0.0001) {
        $conn->rollback();
        http_response_code(400);
        echo json_encode(['code' => 1, 'msg' => 'insufficient balance', 'balance' => $bal, 'userBalance' => $bal]);
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
    cb_log('ok', ['user' => $userId, 'bet' => $bet, 'win' => $win, 'bal' => $newBal, 'serial' => $serial, 'action' => $action]);
    echo json_encode(['code' => 0, 'balance' => $newBal, 'userBalance' => $newBal]);
} catch (Throwable $e) {
    $conn->rollback();
    cb_log('exception', $e->getMessage());
    http_response_code(500);
    echo json_encode(['code' => 1, 'msg' => 'server error']);
}
