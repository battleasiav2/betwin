<?php

use App\Constants\Status;
use App\Lib\Captcha;
use App\Lib\ClientInfo;
use App\Lib\CurlRequest;
use App\Lib\FileManager;
use App\Lib\GoogleAuthenticator;
use App\Models\CommissionLog;
use App\Models\Extension;
use App\Models\Frontend;
use App\Models\GeneralSetting;
use App\Models\Language;
use App\Models\Referral;
use App\Models\Transaction;
use App\Models\User;
use App\Notify\Notify;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Laramin\Utility\VugiChugi;

function systemDetails() {
    $system['name']          = 'xaxino';
    $system['version']       = '3.5';
    $system['build_version'] = '5.1.13';
    return $system;
}

function slug($string) {
    return Str::slug($string);
}

function verificationCode($length) {
    if ($length == 0) {
        return 0;
    }
    $min = pow(10, $length - 1);
    $max = (int) ($min - 1) . '9';
    return random_int($min, $max);
}

function getNumber($length = 8) {
    $characters       = '1234567890';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function activeTemplate($asset = false) {
    $template = session('template') ?? gs('active_template');
    if ($asset) {
        return 'assets/templates/' . $template . '/';
    }
    return 'templates.' . $template . '.';
}

function activeTemplateName() {
    $template = session('template') ?? gs('active_template');
    return $template;
}

function siteLogo($type = null) {
    $name = $type ? "/logo_$type.png" : '/logo.png';
    return getImage(getFilePath('logoIcon') . $name);
}

function siteFavicon() {
    return getImage(getFilePath('logoIcon') . '/favicon.png');
}

function loadReCaptcha() {
    return Captcha::reCaptcha();
}

function loadCustomCaptcha($width = '100%', $height = 46, $bgColor = '#003') {
    return Captcha::customCaptcha($width, $height, $bgColor);
}

function verifyCaptcha() {
    return Captcha::verify();
}

function loadExtension($key) {
    $extension = Extension::where('act', $key)->where('status', Status::ENABLE)->first();
    return $extension ? $extension->generateScript() : '';
}

function getTrx($length = 12) {
    $characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
    $charactersLength = strlen($characters);
    $randomString     = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function getAmount($amount, $length = 2) {
    $amount = round($amount ?? 0, $length);
    return $amount + 0;
}

function showAmount($amount, $decimal = 2, $separate = true, $exceptZeros = false, $currencyFormat = true) {
    $separator = '';
    if ($separate) {
        $separator = ',';
    }
    $printAmount = number_format($amount, $decimal, '.', $separator);
    if ($exceptZeros) {
        $exp = explode('.', $printAmount);
        if ($exp[1] * 1 == 0) {
            $printAmount = $exp[0];
        } else {
            $printAmount = rtrim($printAmount, '0');
        }
    }
    if ($currencyFormat) {
        if (gs('currency_format') == Status::CUR_BOTH) {
            return gs('cur_sym') . $printAmount . ' ' . __(gs('cur_text'));
        } else if (gs('currency_format') == Status::CUR_TEXT) {
            return $printAmount . ' ' . __(gs('cur_text'));
        } else {
            return gs('cur_sym') . $printAmount;
        }
    }
    return $printAmount;
}

function removeElement($array, $value) {
    return array_diff($array, (is_array($value) ? $value : array($value)));
}

function cryptoQR($wallet) {
    return "https://api.qrserver.com/v1/create-qr-code/?data=$wallet&size=300x300&ecc=m";
}

function keyToTitle($text) {
    return ucfirst(preg_replace("/[^A-Za-z0-9 ]/", ' ', $text));
}

function titleToKey($text) {
    return strtolower(str_replace(' ', '_', $text));
}

function strLimit($title = null, $length = 10) {
    return Str::limit($title, $length);
}

function getIpInfo() {
    $ipInfo = ClientInfo::ipInfo();
    return $ipInfo;
}

function osBrowser() {
    $osBrowser = ClientInfo::osBrowser();
    return $osBrowser;
}

function getTemplates() {
    $param['purchasecode'] = env("PURCHASECODE");
    $requestUri            = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $param['website']      = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '' . $requestUri . ' - ' . env("APP_URL");
    $url                   = VugiChugi::gttmp() . systemDetails()['name'];
    $response              = CurlRequest::curlPostContent($url, $param);
    if ($response) {
        return $response;
    } else {
        return null;
    }
}

function getPageSections($arr = false) {
    $jsonUrl  = resource_path('views/') . str_replace('.', '/', activeTemplate()) . 'sections.json';
    $sections = json_decode(file_get_contents($jsonUrl));
    if ($arr) {
        $sections = json_decode(file_get_contents($jsonUrl), true);
        ksort($sections);
    }
    return $sections;
}

function getImage($image, $size = null) {
    $clean = '';
    if (file_exists($image) && is_file($image)) {
        return asset($image) . $clean;
    }
    if ($size) {
        return route('placeholder.image', $size);
    }
    return asset('assets/images/default.png');
}

function notify($user, $templateName, $shortCodes = null, $sendVia = null, $createLog = true, $pushImage = null) {
    $globalShortCodes = [
        'site_name'       => gs('site_name'),
        'site_currency'   => gs('cur_text'),
        'currency_symbol' => gs('cur_sym'),
    ];
    if (gettype($user) == 'array') {
        $user = (object) $user;
    }
    $shortCodes = array_merge($shortCodes ?? [], $globalShortCodes);
    $notify               = new Notify($sendVia);
    $notify->templateName = $templateName;
    $notify->shortCodes   = $shortCodes;
    $notify->user         = $user;
    $notify->createLog    = $createLog;
    $notify->pushImage    = $pushImage;
    $notify->userColumn   = isset($user->id) ? $user->getForeignKey() : 'user_id';
    $notify->send();
}

/**
 * Send HTML mail via configured SMTP. Throws on failure.
 */
function sendSmtpMail(string $toEmail, string $toName, string $subject, string $htmlBody): void
{
    if (!gs('en')) {
        throw new RuntimeException('Email notification is disabled');
    }
    $config = gs('mail_config');
    if (!$config || (($config->name ?? '') !== 'smtp')) {
        throw new RuntimeException('SMTP is not configured');
    }
    $from = (string) (gs('email_from') ?: ($config->username ?? ''));
    $fromName = (string) (gs('email_from_name') ?: gs('site_name') ?: 'BET369WIN');
    if ($from === '' || $toEmail === '') {
        throw new RuntimeException('Missing from/to email address');
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = (string) $config->host;
    $mail->SMTPAuth = true;
    $mail->Username = (string) $config->username;
    $mail->Password = (string) $config->password;
    if (($config->enc ?? 'ssl') === 'ssl') {
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
    }
    $mail->Port = (int) ($config->port ?: 465);
    $mail->CharSet = 'UTF-8';
    $mail->setFrom($from, $fromName);
    $mail->addAddress($toEmail, $toName !== '' ? $toName : explode('@', $toEmail)[0]);
    $mail->addReplyTo($from, $fromName);
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body = $htmlBody;
    $mail->send();
}

function sendPasswordResetCodeMail(object $user, string $code): void
{
    $name = (string) ($user->fullname ?: $user->username ?: explode('@', (string) $user->email)[0]);
    $site = (string) (gs('site_name') ?: 'BET369WIN');
    $subject = $site . ' - Password Reset Code';
    $body = '<p>Hello ' . e($name) . ',</p>'
        . '<p>Your password reset verification code is:</p>'
        . '<p style="font-size:28px;font-weight:700;letter-spacing:6px;">' . e($code) . '</p>'
        . '<p>This code was requested for your ' . e($site) . ' account.</p>'
        . '<p>If you did not request this, you can ignore this email.</p>';

    try {
        sendSmtpMail((string) $user->email, $name, $subject, $body);
        return;
    } catch (Throwable $smtpError) {
        session()->forget('mail_error');
        notify($user, 'PASS_RESET_CODE', [
            'code' => $code,
            'operating_system' => '',
            'browser' => '',
            'ip' => '',
            'time' => '',
        ], ['email'], false);
        if ($mailError = session()->pull('mail_error')) {
            throw new RuntimeException($mailError, 0, $smtpError);
        }
        throw $smtpError;
    }
}

function getPaginate($paginate = null) {
    if (!$paginate) {
        $paginate = gs('paginate_number');
    }
    return $paginate;
}

function paginateLinks($data, $view = null) {
    return $data->appends(request()->all())->links($view);
}

function menuActive($routeName, $type = null, $param = null) {
    if ($type == 3) {
        $class = 'side-menu--open';
    } else if ($type == 2) {
        $class = 'sidebar-submenu__open';
    } else {
        $class = 'active';
    }
    if (is_array($routeName)) {
        foreach ($routeName as $key => $value) {
            if (request()->routeIs($value)) {
                return $class;
            }
        }
    } else if (request()->routeIs($routeName)) {
        if ($param) {
            $routeParam = array_values(isset(request()->route()->parameters) ? request()->route()->parameters : []);
            if (strtolower(isset($routeParam[0]) ? $routeParam[0] : '') == strtolower($param)) {
                return $class;
            } else {
                return;
            }
        }
        return $class;
    }
}

function fileUploader($file, $location, $size = null, $old = null, $thumb = null, $filename = null) {
    $fileManager           = new FileManager($file);
    $fileManager->path     = $location;
    $fileManager->size     = $size;
    $fileManager->old      = $old;
    $fileManager->thumb    = $thumb;
    $fileManager->filename = $filename;
    $fileManager->upload();
    return $fileManager->filename;
}

function fileManager() {
    return new FileManager();
}

function getFilePath($key) {
    // প্রমোশন পাথের জন্য চেক
    if($key == 'promotion') return 'assets/images/promotion';
    return fileManager()->$key()->path;
}

function getFileSize($key) {
    if($key == 'promotion') return '800x450';
    return fileManager()->$key()->size;
}

function getFileExt($key) {
    return fileManager()->$key()->extensions;
}

function diffForHumans($date) {
    $lang = session()->get('lang');
    if (!$lang) {
        $lang = getDefaultLang();
    }
    Carbon::setlocale($lang);
    return Carbon::parse($date)->diffForHumans();
}

function showDateTime($date, $format = 'Y-m-d h:i A') {
    if (!$date) {
        return '-';
    }
    $lang = session()->get('lang');
    if (!$lang) {
        $lang = getDefaultLang();
    }
    Carbon::setlocale($lang);
    return Carbon::parse($date)->translatedFormat($format);
}
function getDefaultLang() {
    return Language::where('is_default', Status::YES)->first()->code ?? 'en';
}

function getContent($dataKeys, $singleQuery = false, $limit = null, $orderById = false) {
    $templateName = activeTemplateName();
    if ($singleQuery) {
        $content = Frontend::where('tempname', $templateName)->where('data_keys', $dataKeys)->orderBy('id', 'desc')->first();
    } else {
        $article = Frontend::where('tempname', $templateName);
        $article->when($limit != null, function ($q) use ($limit) {
            return $q->limit($limit);
        });
        if ($orderById) {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id')->get();
        } else {
            $content = $article->where('data_keys', $dataKeys)->orderBy('id', 'desc')->get();
        }
    }
    return $content;
}

function verifyG2fa($user, $code, $secret = null) {
    $authenticator = new GoogleAuthenticator();
    if (!$secret) {
        $secret = $user->tsc;
    }
    $oneCode  = $authenticator->getCode($secret);
    $userCode = $code;
    if ($oneCode == $userCode) {
        $user->tv = Status::YES;
        $user->save();
        return true;
    } else {
        return false;
    }
}

function urlPath($routeName, $routeParam = null) {
    if ($routeParam == null) {
        $url = route($routeName);
    } else {
        $url = route($routeName, $routeParam);
    }
    $basePath = route('home');
    $path     = str_replace($basePath, '', $url);
    return $path;
}

function showMobileNumber($number) {
    $length = strlen($number);
    return substr_replace($number, '***', 2, $length - 4);
}

function showEmailAddress($email) {
    $endPosition = strpos($email, '@') - 1;
    return substr_replace($email, '***', 1, $endPosition);
}

function getRealIP() {
    $ip = $_SERVER["REMOTE_ADDR"];
    if (filter_var(@$_SERVER['HTTP_FORWARDED'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED'];
    }
    if (filter_var(@$_SERVER['HTTP_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_X_REAL_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_X_REAL_IP'];
    }
    if (filter_var(@$_SERVER['HTTP_CF_CONNECTING_IP'], FILTER_VALIDATE_IP)) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }
    return $ip;
}

function appendQuery($key, $value) {
    return request()->fullUrlWithQuery([$key => $value]);
}

function dateSort($a, $b) {
    return strtotime($a) - strtotime($b);
}

function dateSorting($arr) {
    usort($arr, "dateSort");
    return $arr;
}

function gs($key = null) {
    $general = Cache::get('GeneralSetting');
    if (!$general) {
        $general = GeneralSetting::first();
        Cache::put('GeneralSetting', $general);
    }
    if ($key) {
        return @$general->$key;
    }
    return $general;
}

function isImage($string) {
    $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
    $fileExtension     = pathinfo($string, PATHINFO_EXTENSION);
    if (in_array($fileExtension, $allowedExtensions)) {
        return true;
    } else {
        return false;
    }
}

function isHtml($string) {
    if (preg_match('/<.*?>/', $string)) {
        return true;
    } else {
        return false;
    }
}

function convertToReadableSize($size) {
    preg_match('/^(\d+)([KMG])$/', $size, $matches);
    $size = (int) $matches[1];
    $unit = $matches[2];
    if ($unit == 'G') {
        return $size . 'GB';
    }
    if ($unit == 'M') {
        return $size . 'MB';
    }
    if ($unit == 'K') {
        return $size . 'KB';
    }
    return $size . $unit;
}

function frontendImage($sectionName, $image, $size = null, $seo = false) {
    if ($seo) {
        return getImage('assets/images/frontend/' . $sectionName . '/seo/' . $image, $size);
    }
    return getImage('assets/images/frontend/' . $sectionName . '/' . $image, $size);
}

function levelCommission($id, $amount, $commissionType = '') {
    $usr   = $id;
    $i     = 1;
    $gnl   = gs();
    $level = Referral::count();
    while ($usr != "" || $usr != "0" || $i < $level) {
        $me    = User::find($usr);
        if(!$me) break;
        $refer = User::find($me->ref_by);
        if ($refer == "") {
            break;
        }
        $commission = Referral::where('level', $i)->first();
        if ($commission == null) {
            break;
        }
        $com                  = ($amount * $commission->percent) / 100;
        $referWallet          = User::where('id', $refer->id)->first();
        $newBal                = getAmount($referWallet->balance + $com);
        $referWallet->balance = $newBal;
        $referWallet->save();
        $trx = getTrx();
        $transaction               = new Transaction();
        $transaction->user_id      = $refer->id;
        $transaction->amount       = getAmount($com);
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->remark       = 'commission';
        $transaction->details      = $i . ' level Referral Commission';
        $transaction->trx          = $trx;
        $transaction->post_balance = $newBal;
        $transaction->save();
        $commissionLog           = new CommissionLog();
        $commissionLog->user_id  = $refer->id;
        $commissionLog->who      = $id;
        $commissionLog->level    = $i . ' level Referral Commission';
        $commissionLog->amount   = getAmount($com);
        $commissionLog->main_amo = $newBal;
        $commissionLog->title    = $commissionType;
        $commissionLog->trx      = $trx;
        $commissionLog->save();
        notify($refer, 'REFERRAL_COMMISSION', [
            'amount'       => getAmount($com),
            'post_balance' => $newBal,
            'trx'          => $trx,
            'level'        => $i . ' level Referral Commission',
            'currency'     => $gnl->cur_text,
        ]);
        $usr = $refer->id;
        $i++;
    }
    return 0;
}

function buildResponse($remark, $status, $notify, $data = null) {
    $response = [
        'remark' => $remark,
        'status' => $status,
    ];
    $message = [];
    if ($notify instanceof \Illuminate\Support\MessageBag) {
        $message['error'] = collect($notify)->map(function ($item) {
            return $item[0];
        })->values()->toArray();
    } else {
        $message = [$status => collect($notify)->map(function ($item) {
            if (is_string($item)) {
                return $item;
            }
            if (count($item) > 1) {
                return $item[1];
            }
            return $item[0];
        })->toArray()];
    }
    $response['message'] = $message;
    if ($data) {
        $response['data'] = $data;
    }
    return response()->json($response);
}

function ensureGatewayBonusColumn(): void
{
    static $done = false;
    if ($done || \Illuminate\Support\Facades\Cache::get('gateway_bonus_admin_ready')) {
        $done = true;
        return;
    }
    if (!\Illuminate\Support\Facades\Schema::hasTable('gateway_currencies')) {
        return;
    }
    if (!\Illuminate\Support\Facades\Schema::hasColumn('gateway_currencies', 'deposit_bonus_percent')) {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE gateway_currencies ADD COLUMN deposit_bonus_percent DECIMAL(8,2) NOT NULL DEFAULT 0');
    }
    if (\Illuminate\Support\Facades\Schema::hasTable('general_settings')) {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('general_settings', 'deposit_bonus_percent')) {
            \Illuminate\Support\Facades\DB::statement('ALTER TABLE general_settings ADD COLUMN deposit_bonus_percent DECIMAL(8,2) NOT NULL DEFAULT 5');
        }
        $globalBonus = (float) (\Illuminate\Support\Facades\DB::table('general_settings')->value('deposit_bonus_percent') ?? 0);
        if ($globalBonus <= 0) {
            \Illuminate\Support\Facades\DB::table('general_settings')->update(['deposit_bonus_percent' => 5]);
            $globalBonus = 5;
        }
        if (!\Illuminate\Support\Facades\Cache::get('gateway_bonus_synced_once')) {
            \Illuminate\Support\Facades\DB::table('gateway_currencies')->update(['deposit_bonus_percent' => $globalBonus]);
            \Illuminate\Support\Facades\Cache::forever('gateway_bonus_synced_once', 1);
        }
    }
    \Illuminate\Support\Facades\Cache::forever('gateway_bonus_column', 1);
    \Illuminate\Support\Facades\Cache::forever('gateway_bonus_seeded', 1);
    \Illuminate\Support\Facades\Cache::forever('gateway_bonus_seeded_5pct', 1);
    \Illuminate\Support\Facades\Cache::forever('gateway_bonus_admin_ready', 1);
    $done = true;
}

function ensureAgentColumns(): void
{
    static $done = false;
    if ($done || (\Illuminate\Support\Facades\Cache::get('users_agent_columns') && \Illuminate\Support\Facades\Schema::hasColumn('users', 'agent_code'))) {
        $done = true;
        return;
    }
    if (!\Illuminate\Support\Facades\Schema::hasTable('users')) {
        return;
    }
    $columns = [
        'is_agent' => 'TINYINT NOT NULL DEFAULT 0',
        'agent_percent' => 'DECIMAL(5,2) NOT NULL DEFAULT 0',
        'agent_balance' => 'DECIMAL(28,8) NOT NULL DEFAULT 0',
        'agent_code' => 'VARCHAR(6) NULL DEFAULT NULL',
    ];
    foreach ($columns as $name => $definition) {
        if (!\Illuminate\Support\Facades\Schema::hasColumn('users', $name)) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE users ADD COLUMN {$name} {$definition}");
        }
    }
    $agents = \Illuminate\Support\Facades\DB::table('users')
        ->where('is_agent', 1)
        ->where(function ($query) {
            $query->whereNull('agent_code')->orWhere('agent_code', '');
        })
        ->pluck('id');
    foreach ($agents as $agentId) {
        \Illuminate\Support\Facades\DB::table('users')->where('id', $agentId)->update([
            'agent_code' => generateAgentCode(),
        ]);
    }
    \Illuminate\Support\Facades\Cache::forever('users_agent_columns', 1);
    $done = true;
}

function generateAgentCode(): string
{
    do {
        $code = (string) random_int(100000, 999999);
    } while (\Illuminate\Support\Facades\DB::table('users')->where('agent_code', $code)->exists());
    return $code;
}

function findActiveAgentByReference(?string $reference)
{
    $reference = trim((string) $reference);
    if ($reference === '') {
        return null;
    }
    return \App\Models\User::where('agent_code', $reference)
        ->where('is_agent', 1)
        ->where('status', \App\Constants\Status::USER_ACTIVE)
        ->first();
}

function agentEarnings(int $agentId): array
{
    $now = \Carbon\Carbon::now();
    $binds = [
        $now->copy()->startOfDay()->toDateTimeString(), $now->copy()->endOfDay()->toDateTimeString(),
        $now->copy()->subDay()->startOfDay()->toDateTimeString(), $now->copy()->subDay()->endOfDay()->toDateTimeString(),
        $now->copy()->startOfMonth()->toDateTimeString(), $now->copy()->endOfMonth()->toDateTimeString(),
        $now->copy()->subMonthNoOverflow()->startOfMonth()->toDateTimeString(), $now->copy()->subMonthNoOverflow()->endOfMonth()->toDateTimeString(),
    ];
    $money = 'COALESCE(SUM(amount),0) as total,
        COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN amount ELSE 0 END),0) as today,
        COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN amount ELSE 0 END),0) as yesterday,
        COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN amount ELSE 0 END),0) as this_month,
        COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN amount ELSE 0 END),0) as last_month';
    $count = 'COUNT(*) as total,
        COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END),0) as today,
        COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END),0) as yesterday,
        COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END),0) as this_month,
        COALESCE(SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END),0) as last_month';

    $commission = \App\Models\CommissionLog::where('user_id', $agentId)
        ->where('level', 'like', '%Agent Commission%')
        ->selectRaw($money, $binds)
        ->first();
    $deposits = \App\Models\Deposit::where('status', \App\Constants\Status::PAYMENT_SUCCESS)
        ->whereIn('user_id', function ($query) use ($agentId) {
            $query->select('id')->from('users')->where('ref_by', $agentId);
        })
        ->selectRaw($money, $binds)
        ->first();
    $players = \App\Models\User::where('ref_by', $agentId)->selectRaw($count, $binds)->first();
    $moved = \App\Models\Transaction::where('user_id', $agentId)
        ->where('remark', 'agent_transfer')
        ->selectRaw($money, $binds)
        ->first();

    return [
        'commission_today' => (float) $commission->today,
        'commission_yesterday' => (float) $commission->yesterday,
        'commission_month' => (float) $commission->this_month,
        'commission_last_month' => (float) $commission->last_month,
        'commission_total' => (float) $commission->total,
        'deposit_today' => (float) $deposits->today,
        'deposit_yesterday' => (float) $deposits->yesterday,
        'deposit_month' => (float) $deposits->this_month,
        'deposit_last_month' => (float) $deposits->last_month,
        'deposit_total' => (float) $deposits->total,
        'players_today' => (int) $players->today,
        'players_yesterday' => (int) $players->yesterday,
        'players_month' => (int) $players->this_month,
        'players_last_month' => (int) $players->last_month,
        'players_total' => (int) $players->total,
        'moved_today' => (float) $moved->today,
        'moved_month' => (float) $moved->this_month,
        'moved_last_month' => (float) $moved->last_month,
        'moved_total' => (float) $moved->total,
    ];
}

function manualGatewayWalletNumber($gateway): string
{
    if (!$gateway) {
        return '';
    }
    $raw = $gateway->gateway_parameters ?? null;
    if (is_string($raw)) {
        $raw = json_decode($raw);
    }
    if (is_array($raw)) {
        $raw = (object) $raw;
    }
    return trim((string) ($raw->wallet_number ?? ''));
}

/**
 * Ensure bKash / Nagad / Rocket manual deposit methods exist (admin can edit numbers).
 */
function ensureManualMobileBanks(): void
{
    static $done = false;
    if ($done || \Illuminate\Support\Facades\Cache::get('manual_mobile_banks_ready_v1')) {
        $done = true;
        return;
    }
    if (!\Illuminate\Support\Facades\Schema::hasTable('gateways') || !\Illuminate\Support\Facades\Schema::hasTable('forms')) {
        return;
    }
    ensureGatewayBonusColumn();

    $banks = [
        ['alias' => 'bkash', 'name' => 'bKash', 'instruction' => 'Send money (Payment) to the bKash number shown, then submit the TRX ID.'],
        ['alias' => 'nagad', 'name' => 'Nagad', 'instruction' => 'Send money (Payment) to the Nagad number shown, then submit the TRX ID.'],
        ['alias' => 'rocket', 'name' => 'Rocket', 'instruction' => 'Send money (Payment) to the Rocket number shown, then submit the TRX ID.'],
    ];

    $lastCode = (int) (\App\Models\Gateway::manual()->orderByDesc('code')->value('code') ?: 999);

    foreach ($banks as $bank) {
        $exists = \App\Models\Gateway::where(function ($q) use ($bank) {
            $q->where('alias', $bank['alias'])->orWhere('name', $bank['name']);
        })->first();
        if ($exists) {
            continue;
        }

        $form = new \App\Models\Form();
        $form->act = 'manual_deposit';
        $form->form_data = [
            'trx_id' => [
                'name' => 'TRX ID',
                'label' => 'trx_id',
                'is_required' => 'required',
                'instruction' => 'Enter the transaction ID from your payment SMS',
                'extensions' => '',
                'options' => [],
                'type' => 'text',
                'width' => '12',
            ],
        ];
        $form->save();

        $lastCode++;
        $method = new \App\Models\Gateway();
        $method->code = $lastCode;
        $method->form_id = $form->id;
        $method->name = $bank['name'];
        $method->alias = $bank['alias'];
        $method->image = null;
        $method->status = \App\Constants\Status::ENABLE;
        $method->gateway_parameters = json_encode(['wallet_number' => '']);
        $method->supported_currencies = [];
        $method->crypto = \App\Constants\Status::DISABLE;
        $method->description = $bank['instruction'];
        $method->save();

        $currency = new \App\Models\GatewayCurrency();
        $currency->name = $bank['name'];
        $currency->gateway_alias = $bank['alias'];
        $currency->currency = gs('cur_text') ?: 'BDT';
        $currency->symbol = '';
        $currency->method_code = $lastCode;
        $currency->min_amount = 100;
        $currency->max_amount = 25000;
        $currency->fixed_charge = 0;
        $currency->percent_charge = 0;
        $currency->deposit_bonus_percent = (float) (gs('deposit_bonus_percent') ?? 5);
        $currency->rate = 1;
        $currency->save();
    }

    \Illuminate\Support\Facades\Cache::forever('manual_mobile_banks_ready_v1', 1);
    $done = true;
}

function responseSuccess($remark, $notify, $data = null) {
    return buildResponse($remark, 'success', $notify, $data);
}

function responseError($remark, $notify, $data = null) {
    return buildResponse($remark, 'error', $notify, $data);
}