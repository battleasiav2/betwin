<?php
/**
 * RapidVerse seamless wallet callback.
 * Accepts snake_case + camelCase payloads; supports balance/bet/win/settle.
 * Resolves players by numeric id OR username/mobile/member_account.
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

    // Short public-ish hit marker for ops (no secrets)
    $marker = __DIR__ . '/core/storage/logs/rv_cb_last.txt';
    @file_put_contents($marker, date('c') . ' ' . $msg . "\n", FILE_APPEND);
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

function cb_ok(float $bal, array $extra = []): void
{
    echo json_encode(array_merge([
        'code'        => 0,
        'status'      => 1,
        'success'     => true,
        'msg'         => 'ok',
        'balance'     => $bal,
        'userBalance' => $bal,
        'currency'    => 'BDT',
    ], $extra));
}

// Health / last-hit probe (no secrets)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $marker = __DIR__ . '/core/storage/logs/rv_cb_last.txt';
    $tail = is_readable($marker) ? trim(implode('', array_slice(file($marker), -5))) : 'no hits yet';
    echo json_encode(['code' => 0, 'msg' => 'callback alive', 'recent' => $tail]);
    exit;
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
    'pass' => 'z5BO=Zu8e^;P',
    'name' => 'u811189100_betwin',
];
$secretKey = '';
$apiToken = '';
$apiPrefix = 'nix6260006107'; // RapidVerse "API PREFIX" — stripped from callback userIds

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
            'RAPIDVERSE_API_PREFIX' => $apiPrefix = $value,
            default => null,
        };
    }
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: 'null', true);
if (!is_array($data)) {
    // form-urlencoded / multipart fallback
    if (!empty($_POST)) {
        $data = $_POST;
    } else {
        parse_str((string) $raw, $parsed);
        $data = is_array($parsed) ? $parsed : null;
    }
}
if (!is_array($data)) {
    cb_log('invalid_json', $raw);
    http_response_code(400);
    echo json_encode(['code' => 1, 'msg' => 'invalid json']);
    exit;
}

// Flatten one level if nested under data/payload
if (isset($data['data']) && is_array($data['data']) && !isset($data['userId']) && !isset($data['user_id'])) {
    $data = array_merge($data, $data['data']);
}
if (isset($data['payload']) && is_array($data['payload'])) {
    $data = array_merge($data, $data['payload']);
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
    $row = $conn->query('SELECT secret_key, api_token, agent_user FROM api_game_settings ORDER BY id ASC LIMIT 1');
    if ($row && ($s = $row->fetch_assoc())) {
        if (!empty($s['secret_key'])) {
            $secretKey = $s['secret_key'];
        }
        if (!empty($s['api_token'])) {
            $apiToken = $s['api_token'];
        }
        // Prefer agent_user + digits if a dedicated prefix column is absent
        if (!empty($s['agent_user']) && $apiPrefix === 'nix6260006107') {
            // keep default; prefix is from RapidVerse panel
        }
    }
    // Optional api_prefix column
    $col = $conn->query("SHOW COLUMNS FROM api_game_settings LIKE 'api_prefix'");
    if ($col && $col->num_rows > 0) {
        $row2 = $conn->query('SELECT api_prefix FROM api_game_settings ORDER BY id ASC LIMIT 1');
        if ($row2 && ($p = $row2->fetch_assoc()) && !empty($p['api_prefix'])) {
            $apiPrefix = $p['api_prefix'];
        }
    }
}

if ($secretKey === '' && $apiToken === '') {
    http_response_code(503);
    echo json_encode(['code' => 1, 'msg' => 'callback auth not configured']);
    exit;
}

// Collect headers from getallheaders + $_SERVER (Hostinger/CGI often drops getallheaders)
$headerMap = [];
if (function_exists('getallheaders')) {
    foreach (getallheaders() ?: [] as $hk => $hv) {
        $headerMap[strtolower((string) $hk)] = trim((string) $hv);
    }
}
foreach ($_SERVER as $sk => $sv) {
    if (str_starts_with($sk, 'HTTP_') && is_string($sv)) {
        $name = strtolower(str_replace('_', '-', substr($sk, 5)));
        if (!isset($headerMap[$name])) {
            $headerMap[$name] = trim($sv);
        }
    }
}

$providedSecret = $headerMap['x-secret-key']
    ?? $headerMap['x-api-secret']
    ?? $headerMap['secret-key']
    ?? $headerMap['api-secret']
    ?? $headerMap['secret']
    ?? ($_GET['secret_key'] ?? $_GET['secretKey'] ?? $_GET['secret'] ?? null)
    ?? cb_val($data, ['secret_key', 'secretKey', 'secret', 'agentSecret', 'agent_secret'], '');
$providedToken = $headerMap['x-api-token']
    ?? $headerMap['x-api-key']
    ?? $headerMap['api-token']
    ?? $headerMap['api-key']
    ?? $headerMap['authorization']
    ?? ($_GET['api_token'] ?? $_GET['apiToken'] ?? $_GET['token'] ?? null)
    ?? cb_val($data, ['api_token', 'apiToken', 'token', 'agentToken', 'agent_token'], '');
if (str_starts_with(strtolower((string) $providedToken), 'bearer ')) {
    $providedToken = trim(substr($providedToken, 7));
}

$sigHeader = $headerMap['x-signature']
    ?? $headerMap['x-r4nkt-signature']
    ?? $headerMap['signature']
    ?? ($_GET['signature'] ?? '');

$authOk = false;
if ($secretKey !== '' && $providedSecret !== '' && hash_equals($secretKey, (string) $providedSecret)) {
    $authOk = true;
}
if (!$authOk && $apiToken !== '' && $providedToken !== '' && hash_equals($apiToken, (string) $providedToken)) {
    $authOk = true;
}
// Bearer may carry either token OR secret
if (!$authOk && $providedToken !== '') {
    if ($secretKey !== '' && hash_equals($secretKey, (string) $providedToken)) {
        $authOk = true;
    }
}
if (!$authOk && $secretKey !== '' && $sigHeader !== '') {
    $expected = hash_hmac('sha256', $raw, $secretKey);
    if (hash_equals($expected, strtolower($sigHeader)) || hash_equals($expected, $sigHeader)) {
        $authOk = true;
    }
    $expected2 = hash_hmac('sha256', $raw, $apiToken);
    if (!$authOk && $apiToken !== '' && (hash_equals($expected2, strtolower($sigHeader)) || hash_equals($expected2, $sigHeader))) {
        $authOk = true;
    }
}

if (!$authOk) {
    cb_log('unauthorized', [
        'headers' => array_keys($headerMap),
        'keys' => array_keys($data),
        'has_secret_hdr' => isset($headerMap['x-secret-key']) || isset($headerMap['secret-key']),
        'has_token_hdr' => isset($headerMap['x-api-token']) || isset($headerMap['authorization']),
        'get' => array_keys($_GET),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
    ]);
    http_response_code(401);
    echo json_encode(['code' => 1, 'msg' => 'unauthorized']);
    exit;
}

cb_log('hit', ['keys' => array_keys($data), 'action' => cb_val($data, ['action', 'type', 'event', 'method', 'cmd', 'command'], ''), 'rawUser' => cb_val($data, ['userId', 'user_id', 'member_account'], '')]);

// Resolve player: numeric id OR username / mobile / member_account
// RapidVerse adds API PREFIX to userIds (e.g. nix6260006107 + 28 => nix626000610728)
$rawUser = cb_val($data, [
    'user_id', 'userId', 'member_id', 'memberId', 'uid', 'player_id', 'playerId',
    'member_account', 'memberAccount', 'account', 'username', 'login', 'user', 'player',
], '');
$userId = 0;
$lookup = trim((string) $rawUser);
if ($lookup !== '' && $apiPrefix !== '' && str_starts_with($lookup, $apiPrefix)) {
    $lookup = substr($lookup, strlen($apiPrefix));
    cb_log('prefix_stripped', ['prefix' => $apiPrefix, 'user' => $lookup]);
}
if ($lookup !== '') {
    // Auto-increment ids are digits without a leading zero (mobiles like 0177… stay as username lookup)
    if (preg_match('/^[1-9]\d{0,9}$/', $lookup)) {
        $userId = (int) $lookup;
    }
    if ($userId <= 0) {
        $q = $conn->prepare('SELECT id FROM users WHERE username=? OR mobile=? OR email=? LIMIT 1');
        $q->bind_param('sss', $lookup, $lookup, $lookup);
        $q->execute();
        $found = $q->get_result()->fetch_assoc();
        if ($found) {
            $userId = (int) $found['id'];
        }
    }
}

$gameName = (string) cb_val($data, ['game_code', 'gameCode', 'game_id', 'gameId', 'game_name', 'gameName', 'game_uid'], 'API Game');
$gameName = substr(trim($gameName), 0, 120) ?: 'API Game';
$serial = trim((string) cb_val($data, [
    'serial_number', 'serialNumber', 'transaction_id', 'transactionId', 'txn_id', 'txnId',
    'bet_id', 'betId', 'round_id', 'roundId', 'uniqid', 'id', 'transactionCode',
], ''));
$action = strtolower((string) cb_val($data, ['action', 'type', 'event', 'method', 'cmd', 'command'], 'settle'));

$bet = (float) cb_val($data, ['bet_amount', 'betAmount', 'bet', 'stake', 'debit_amount', 'debitAmount', 'withdraw'], 0);
$win = (float) cb_val($data, ['win_amount', 'winAmount', 'win', 'payout', 'credit_amount', 'creditAmount', 'deposit'], 0);
$amount = (float) cb_val($data, ['amount', 'money', 'value'], 0);

// Absolute balance push (some aggregators send final balance)
$setBal = cb_val($data, ['userBalance', 'user_balance', 'currentBalance', 'current_balance', 'balance_after'], null);

// Map action-based amount into bet/win
if ($amount != 0.0 && $bet == 0.0 && $win == 0.0) {
    if (in_array($action, ['bet', 'debit', 'withdraw', 'stake', 'wager', 'withdrawrequest'], true)) {
        $bet = abs($amount);
    } elseif (in_array($action, ['win', 'credit', 'deposit', 'payout', 'prize', 'depositrequest'], true)) {
        $win = abs($amount);
    } elseif (in_array($action, ['refund', 'cancel', 'rollback', 'cancelbet', 'cancelwin'], true)) {
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
    cb_log('invalid_user', ['raw' => $rawUser, 'data' => $data]);
    http_response_code(400);
    echo json_encode(['code' => 1, 'msg' => 'invalid user']);
    exit;
}

// Balance inquiry only
$isBalanceQuery = in_array($action, ['balance', 'getbalance', 'get_balance', 'query', 'get_user_balance'], true)
    || ($bet == 0.0 && $win == 0.0 && $setBal === null && $serial === '' && !in_array($action, ['bet', 'win', 'debit', 'credit', 'withdraw', 'deposit'], true));
if ($isBalanceQuery) {
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
    cb_ok($bal);
    exit;
}

// Absolute set-balance sync
if ($setBal !== null && $bet == 0.0 && $win == 0.0 && in_array($action, ['settle', 'sync', 'update', 'setbalance', 'set_balance', 'balance_update'], true)) {
    $newBal = round((float) $setBal, 2);
    if ($newBal < 0) {
        $newBal = 0.0;
    }
    $u = $conn->prepare('UPDATE users SET balance=? WHERE id=?');
    $u->bind_param('di', $newBal, $userId);
    $u->execute();
    cb_log('set_balance', ['user' => $userId, 'bal' => $newBal]);
    cb_ok($newBal);
    exit;
}

if ($serial === '' || strlen($serial) > 190) {
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
        cb_ok($bal, ['msg' => 'duplicate']);
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
        echo json_encode(['code' => 1, 'msg' => 'insufficient balance', 'balance' => $bal, 'userBalance' => $bal, 'status' => 0]);
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
    cb_ok($newBal);
} catch (Throwable $e) {
    $conn->rollback();
    cb_log('exception', $e->getMessage());
    http_response_code(500);
    echo json_encode(['code' => 1, 'msg' => 'server error']);
}
