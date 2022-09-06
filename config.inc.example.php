<?php
//Rename to config.inc.php !!!
define('CONFIG', [
    'client_ip' => $_SERVER["HTTP_X_REAL_IP"], //Use $_SERVER["REMOTE_ADDR"] if not behind nginx proxy
    'responses_password_bcrypt' => 'supersecretbcrypthash',
    'mysql' => [
        'host' => 'localhost',
        'user' => 'supersecretuser',
        'password' => 'supersecretpassword',
        'database' => 'supersecretdb'
    ],
    'hcaptcha' => [
        'sitekey' => 'supersecretsitekey',
        'secret' => 'supersecretsecretlol'
    ],
    'telegram_alert' => [
        'enabled' => true,
        'token' => 'supersecrettoken',
        'chat_id' => 'supersecretchatid'
    ]
]);