<?php
require("../config.inc.php");
header("Content-type: application/json");

if (!isset($_POST["email"]) || !isset($_POST["name"]) || !isset($_POST["title"]) || !isset($_POST["message"]) || !isset($_POST["h-captcha-response"])) {
    die(json_encode(["success"=>false,"msg"=>"Invalid request."]));
} 

$email = $_POST["email"];
$name = htmlspecialchars(trim($_POST["name"]));
$title = htmlspecialchars(trim($_POST["title"]));
$msg = htmlspecialchars(trim($_POST["message"]));
$captcha = htmlspecialchars($_POST["h-captcha-response"]);

$errors = [];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "The submitted email address is invalid.";
}

$name_len = strlen($name);
if ($name_len < 4 || $name_len > 24) {
    $errors[] = "The submitted name is invalid.";
}

$title_len = strlen($title);
if ($title_len < 8 || $title_len > 64) {
    $errors[] = "The submitted title is invalid.";
}

$msg_len = strlen($msg);
if ($msg_len < 50 || $msg_len > 3000) {
    $errors[] = "The submitted message is invalid.";
}

$ip = CONFIG['client_ip'];
$catpcha_response = json_decode(file_get_contents("https://hcaptcha.com/siteverify?secret=".CONFIG['hcaptcha']['secret']."&response={$captcha}&remoteip={$ip}"), true);
if ($catpcha_response["success"] != true) {
    $errors[] = "The submitted captcha is invalid.";
}

if (sizeof($errors) > 0) {
    die(json_encode(["success"=>false,"msg"=>implode("\r\n", $errors)]));
}

try {
    $mysql = CONFIG['mysql'];
    $db = new PDO("mysql:host={$mysql['host']};dbname={$mysql['database']}", $mysql['user'], $mysql['password']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email_domain = explode("@", $email)[1];

    $stmt = $db->prepare("SELECT * FROM `banned-emails` WHERE domain=?");
    $stmt->execute([$email_domain]); 
    
    if (sizeof($stmt->fetchAll()) > 0) {
        die(json_encode(["success"=>false,"msg"=>"Your email provider's domain is not allowed. Please use another email address."]));
    }

} catch(PDOException $e) {
    die(json_encode(["success"=>false,"msg"=>"An internal error has occured. Try later."]));
}

if (sizeof($errors) > 0) {
    die(json_encode(["success"=>false,"msg"=>implode("\r\n", $errors)]));
}

$time = time();

function telegramMsg($text) {
    if (CONFIG['telegram_alert']['enabled'] != true) {
        return;
    }
    
    $data = [
        'chat_id' => CONFIG['telegram_alert']['chat_id'],
        'text' => $text
    ];
    file_get_contents("https://api.telegram.org/bot".CONFIG['telegram_alert']['token']."/sendMessage?" . http_build_query($data));
}


try {
    $db->prepare("INSERT INTO responses (time, email, name, title, text) VALUES (?,?,?,?,?)")->execute([$time, $email, $name, $title, $msg]);

    if (CONFIG['telegram_alert']['enabled'] == true) {
        telegramMsg("New contact me message from {$name} ({$email})");
    }

    die(json_encode(["success"=>true,"msg"=>"Your message has been sent."]));
} catch(PDOException $e) {
    die(json_encode(["success"=>false,"msg"=>"An internal error has occured. Try later."]));
}
?>