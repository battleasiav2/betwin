<?php
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo 'POST only';
    exit;
}
if (!hash_equals('Bet369SmtpFix2026', (string) ($_POST['key'] ?? ''))) {
    http_response_code(403);
    echo 'Forbidden';
    exit;
}
$username = trim((string) ($_POST['username'] ?? ''));
$password = (string) ($_POST['password'] ?? '');
$from = trim((string) ($_POST['from'] ?? $username));
$fromName = trim((string) ($_POST['from_name'] ?? 'BET369WIN'));
$host = trim((string) ($_POST['host'] ?? 'smtp.hostinger.com'));
$port = (int) ($_POST['port'] ?? 465);
$enc = trim((string) ($_POST['enc'] ?? 'ssl'));
$testTo = trim((string) ($_POST['test_to'] ?? 'battleasia6@gmail.com'));
if ($username === '' || $password === '') {
    http_response_code(422);
    echo 'username/password required';
    exit;
}

try {
    require __DIR__ . '/core/vendor/autoload.php';
    $app = require __DIR__ . '/core/bootstrap/app.php';
    $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

    $mailConfig = [
        'name' => 'smtp',
        'host' => $host,
        'port' => (string) $port,
        'enc' => $enc,
        'username' => $username,
        'password' => $password,
    ];

    $gs = App\Models\GeneralSetting::query()->firstOrFail();
    $gs->en = 1;
    $gs->email_from = $from;
    $gs->email_from_name = $fromName;
    if (empty($gs->email_template)) {
        $gs->email_template = '<div>{{message}}</div>';
    }
    $gs->mail_config = $mailConfig;
    $gs->save();

    Illuminate\Support\Facades\Cache::forget('GeneralSetting');
    Illuminate\Support\Facades\Cache::flush();

    foreach (['PASS_RESET_CODE', 'PASS_RESET_DONE', 'DEFAULT'] as $act) {
        $row = Illuminate\Support\Facades\DB::table('notification_templates')->where('act', $act)->first();
        $update = [
            'email_status' => 1,
            'email_sent_from_address' => $from,
            'email_sent_from_name' => $fromName,
        ];
        if ($row && trim((string) ($row->email_body ?? '')) === '') {
            $update['email_body'] = $act === 'PASS_RESET_CODE'
                ? 'Your password reset code is: {{code}}'
                : '{{message}}';
        }
        if ($row && trim((string) ($row->subject ?? '')) === '') {
            $update['subject'] = $act === 'PASS_RESET_CODE' ? 'Password Reset Code' : 'Notification';
        }
        Illuminate\Support\Facades\DB::table('notification_templates')->where('act', $act)->update($update);
    }

    echo "OK smtp+cache cleared\n";
    echo 'mail_name=' . (string) (gs('mail_config')->name ?? '') . "\n";
    echo 'en=' . (int) gs('en') . "\n";

    $tpl = Illuminate\Support\Facades\DB::table('notification_templates')->where('act', 'PASS_RESET_CODE')->first();
    echo 'tpl_status=' . (int) ($tpl->email_status ?? -1) . ' body_len=' . strlen((string) ($tpl->email_body ?? '')) . "\n";

    $errors = Illuminate\Support\Facades\DB::table('admin_notifications')->orderByDesc('id')->limit(5)->get(['id', 'title', 'created_at']);
    echo "recent_admin_errors:\n";
    foreach ($errors as $err) {
        echo '- #' . $err->id . ' ' . $err->created_at . ' ' . $err->title . "\n";
    }

    $code = (string) random_int(100000, 999999);
    $user = (object) [
        'email' => $testTo,
        'fullname' => explode('@', $testTo)[0],
        'username' => explode('@', $testTo)[0],
    ];
    sendPasswordResetCodeMail($user, $code);
    echo "SMTP_CODE_SENT_OK to {$testTo} code={$code}\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'FAIL ' . $e->getMessage();
    exit;
}

@unlink(__FILE__);
echo "removed\n";
