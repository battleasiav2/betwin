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
    $gs->mail_config = $mailConfig;
    $gs->save();

    Illuminate\Support\Facades\Cache::forget('GeneralSetting');
    Illuminate\Support\Facades\Cache::flush();

    foreach (['PASS_RESET_CODE', 'PASS_RESET_DONE', 'DEFAULT'] as $act) {
        Illuminate\Support\Facades\DB::table('notification_templates')
            ->where('act', $act)
            ->update([
                'email_status' => 1,
                'email_sent_from_address' => $from,
                'email_sent_from_name' => $fromName,
            ]);
    }

    echo "OK smtp+cache cleared\n";
    echo 'mail_name=' . (string) (gs('mail_config')->name ?? '') . "\n";
    echo 'en=' . (int) gs('en') . "\n";

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = $host;
    $mail->SMTPAuth = true;
    $mail->Username = $username;
    $mail->Password = $password;
    $mail->SMTPSecure = $enc === 'ssl'
        ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
        : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = $port;
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($from, $fromName);
    $mail->addAddress($testTo);
    $mail->isHTML(true);
    $mail->Subject = 'BET369WIN SMTP OK';
    $mail->Body = 'Live SMTP working at ' . date('c') . '. You can use Forgot Password now.';
    $mail->send();
    echo "SMTP_SENT_OK to {$testTo}\n";
} catch (Throwable $e) {
    http_response_code(500);
    echo 'FAIL ' . $e->getMessage();
    exit;
}

@unlink(__FILE__);
echo "removed\n";
