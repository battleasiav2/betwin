<?php

// 读取隐藏的密钥文件
$secretFile = base_path('storage/framework/.sys_demo.php');

// 默认值（如果文件或env都没设）
$panelPassword = env('PAYOUT_PANEL_PASSWORD', '123456');
$formPin       = env('PAYOUT_FORM_PIN', '1234');

if (file_exists($secretFile)) {
    $secret = require $secretFile;

    if (is_array($secret)) {
        if (!empty($secret['panel_password'])) {
            $panelPassword = (string) $secret['panel_password'];
        }

        if (!empty($secret['form_pin'])) {
            $formPin = (string) $secret['form_pin'];
        }
    }
}

return [
    // 面板密码（6位）
    'panel_password' => $panelPassword,

    // 提交PIN码（4位）
    'form_pin'       => $formPin,
];