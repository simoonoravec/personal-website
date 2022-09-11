<?php
require("./config.inc.php");
$bday = new DateTime('27.11.2002');
$today = new Datetime(date('m.d.y'));
$diff = $today->diff($bday);

$month_string = ($diff->m > 1) ? 'months':'month';
$day_string = ($diff->d > 1) ? 'days':'day';

$age = "{$diff->y} years, {$diff->m} {$month_string}, {$diff->d} {$day_string}";

$rand = 'e_'.bin2hex(openssl_random_pseudo_bytes(3));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&family=Oxygen&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://static.0r4v3c.xyz/icons.min.css">
    <link rel="stylesheet" href="https://static.0r4v3c.xyz/cute-alert/cute-alert.css">
    <title>Šimon Oravec - Personal domain</title>
</head>
<body>
    <div class="navbar">
        <ul>
            <li><a id="navlink-home" href="#home">HOME</a></li>
            <li><a id="navlink-about" href="#about">ABOUT ME</a></li>
            <li><a id="navlink-contact" href="#contact">CONTACT ME</a></li>
        </ul>
    </div>
    <div class="container">
        <noscript><div style="text-align: center;"><h2 class="red">This website requires JavaScript to work.</h2>Please enable JavaScript and refresh the site</div></noscript>
        <section id="home" style="display:none;">
            <img class="avatar" src="/assets/img/dummy.png">
            <div class="text-center">
                <h3>Šimon Oravec</h3>
                <p>Welcome to my personal domain.</p>
                <b>Age:</b> <?=$age?><br>
                <b>Lives in</b> Presov, Slovakia<br>
            </div>
            <p class="text-center" id="room-temp"></p>
            <div class="text-center">
                <a href="https://github.com/simoonoravec" target="_blank" class="github-logo icon icon-github-white"></a>
            </div>
        </section>

        <section id="about" style="display:none;">
            <h2 class="text-center">About me</h2>
            <p>I started with <span class="white">HTML</span> and <span class="white">CSS</span> when I was 13 years old, then I jumped to <span class="white">PHP</span> and eventually <span class="white">JavaScript</span> and <span class="white">Java</span> (creating Minecraft plugins at first, I even created a few fairly simple Minecraft mini-games). Now I'm primarily focusing on backend web development mainly using <a href="https://nette.org/" target="_blank">Nette Framework</a> (PHP) or <a href="https://expressjs.com/" target="_blank">Express</a> (NodeJS).</p>
            <p>My favourite and preffered operating system to use for development is <a href="https://getfedora.org/" target="_blank">Linux Fedora</a> but I also use <span class="white">Windows</span> (11) for other things (graphics, gaming, etc.). My preffered server OS is <a href="https://www.debian.org/" target="_blank">Linux Debian</a> because it's very lightweight, stable and has great support of all kinds of server software. I am not very familiar with Windows Server becuase I currently don't have any use case for it.</p>
            <p>I am also very interested in computer hardware and building custom computers.</p>
            <div class="text-center">
                <h3>Software & languages I use</h3>
                <a loading="lazy" href="https://en.wikipedia.org/wiki/Linux" target="_blank" class="skill-img icon icon-linux" title="Linux"></a>
                <a loading="lazy" href="https://en.wikipedia.org/wiki/HTML" target="_blank" class="skill-img icon icon-html" title="HTML"></a>
                <a loading="lazy" href="https://en.wikipedia.org/wiki/JavaScript" target="_blank" class="skill-img icon icon-javascript" title="JavaScript"></a>
                <a loading="lazy" href="https://en.wikipedia.org/wiki/Java_(software_platform)" target="_blank" class="skill-img icon icon-java" title="Java"></a>
                <a loading="lazy" href="https://en.wikipedia.org/wiki/PHP" target="_blank" class="skill-img icon icon-php" title="PHP"></a>
                <a loading="lazy" href="https://en.wikipedia.org/wiki/Node.js" target="_blank" class="skill-img icon icon-nodejs" title="Node.js"></a>
                <a loading="lazy" href="https://en.wikipedia.org/wiki/C_Sharp_(programming_language)" target="_blank" class="skill-img icon icon-csharp" title="C#"></a>
                <a loading="lazy" href="https://en.wikipedia.org/wiki/MySQL" target="_blank" class="skill-img icon icon-myqsl" title="MySQL"></a>
                <a loading="lazy" href="https://nette.org/" target="_blank" class="skill-img icon icon-nette" title="Nette Framework"></a>
            </div> 
        </section>

        <section id="contact" style="display:none;">
                <h2 class="text-center">Contact me</h2>
                <div id="contact-box">
                    <p>Alternatively, you can email me at <span class="white" id="<?=$rand?>" style="user-select:all;border-bottom: 1px dotted #fff;">[PROTECTED]</span></p>
                    <form method="POST" id="contactform">
                        <input class="input" name="email" type="email" placeholder="Your email address" maxlength="32">
                        <input class="input" name="name" type="text" placeholder="What can I call you?" maxlength="24">
                        <input class="input input-wide" name="title" type="text" placeholder="Short title for your message" maxlength="64">
                        <textarea class="contact-message autoexpand" id="message" name="message" placeholder="Your message"></textarea>
                        <small style="float:right;margin-right:8%;"><span id="message-length">0</span> / 3000 characters</small>
                        <div class="h-captcha" data-sitekey="<?=CONFIG['hcaptcha']['sitekey']?>" style="margin-top:30px;"></div>
                        <button class="btn" id="contactform_submitbtn">Send message</button>
                    </form>
                </div>
                <div id="success-splash" style="display:none" class="text-center">
                    <img style="width:13rem;height:10rem;" src="/assets/img/checkmark.svg">
                    <p><h3 style="margin:0;">The message has been sent!</h3>I will usually reply to your email in the next 48 hours.</p>
                </div>
        </section>
    </div>
    
<script src="https://static.0r4v3c.xyz/jquery/3.5.1/jquery.min.js"></script>
<script src="https://static.0r4v3c.xyz/cute-alert/cute-alert.js"></script>
<script src="/assets/js/main.js"></script>
<script src="/assets/js/contact.js"></script>
<script>
function a(y,g){var D=x();return a=function(q,e){q=q-0x1b8;var J=D[q];return J;},a(y,g);}function x(){var g=['<?=$rand?>','reverse','innerHTML','JiN4NzM7JiN4Njk7JiN4NmQ7JiN4NmY7JiN4NmU7JiN4NDA7JiN4MzA7JiN4N','getElementById','zI7JiN4MzQ7JiN4NzY7JiN4MzM7JiN4NjM7JiN4MmU7JiN4Nzg7JiN4Nzk7JiN4N2E7','join'];x=function(){return g;};return x();}var y=a;document[y(0x1bc)](y(0x1b8))[y(0x1ba)]=atob([y(0x1bd),y(0x1bb)][y(0x1b9)]()[y(0x1be)](''));
</script>
</body>
</html>