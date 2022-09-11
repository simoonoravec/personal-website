<?php
require("./config.inc.php");

session_start();
if ($_SESSION["login"] != 1) {
    if (isset($_POST["password"])) {
        if (password_verify($_POST["password"], CONFIG['responses_password_bcrypt'])) {
            $_SESSION["alert"] = ["green", "Welcome!"];
            $_SESSION["login"] = 1;
        } else {
            $_SESSION["alert"] = ["red", "Incorrect password."];
        }
        die(header("Location: /responses"));
    }
} else {
    try {
        $mysql = CONFIG['mysql'];
        $db = new PDO("mysql:host={$mysql['host']};dbname={$mysql['database']}", $mysql['user'], $mysql['password']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_GET["id"])) {
            if (isset($_GET["read"])) {
                $items = $db->query("SELECT * FROM responses WHERE status=2")->fetchAll();
            } else {
                $items = $db->query("SELECT * FROM responses WHERE status=1")->fetchAll();
            }
        } else {
            $id = $_GET["id"];

            if (!is_int(intval($id))) {
                $_SESSION["alert"] = ["red", "This response does not exist."];
                die(header("Location: /responses"));
            }

            $stmt = $db->prepare("SELECT * FROM responses WHERE id = ?");
            $stmt->execute([$id]);

            $item = $stmt->fetch();
            if ($item == null) {
                $_SESSION["alert"] = ["red", "This response does not exist."];
                die(header("Location: /responses"));
            }

            if (isset($_GET["statusupdate"])) {
                $status = $_GET["statusupdate"];
                if (!is_int(intval($status))) {
                    $_SESSION["alert"] = ["red", "Invalid new status."];
                    die(header("Location: /responses?id={$id}"));
                }

                if ($db->prepare("UPDATE responses SET status = ? WHERE id = ?")->execute([$status, $id])) {
                    $_SESSION["alert"] = ["green", "Status has been updated."];
                } else {
                    $_SESSION["alert"] = ["red", "An internal error has occured. Try later."];
                }
                die(header("Location: /responses?id={$id}"));
            }

            $time = date("d.m.Y - G:i (T)", $item["time"]);
        }
    } catch(PDOException $e) {
        die("<h3>Database error.</h3>");
    }
}
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
    <div class="navbar text-center">
        <h3>Contact form responses</h3>
        <?php if (isset($_SESSION["alert"])) { ?>
            <p style="font-size:15px;font-weight:100;" class="<?=$_SESSION["alert"][0]?>"><?=$_SESSION["alert"][1]?></p>
        <?php unset($_SESSION["alert"]); } ?>
    </div>
    <div class="container">
        <noscript><div style="text-align: center;"><h2 class="red">This website requires JavaScript to work.</h2>Please enable JavaScript and refresh the site</div></noscript>

        <section id="main" style="display:none;">
        <?php if ($_SESSION["login"] != 1) { ?>
            <div class="text-center">
                <p><a href="/">&#8617; Return to website</a></p>
                <form method="POST">
                    <input class="input mg-auto" name="password" type="password" placeholder="Enter the access password" required>
                    <button class="btn" id="contactform_submitbtn">Verify & continue</button>
                </form>
            </div>
        <?php 
        } else {
        if (isset($_GET["id"])) { ?>
        <p><a href="/responses">&#8617; Go back</a></p>
        <div class="p-nomargin">
            <p>Name: <b><?=htmlspecialchars($item["name"])?></b></p>
            <p>Email: <b style="user-select:all;"><?=htmlspecialchars($item["email"])?></b></p>
            <p>Time: <b><?=$time?></b></p>
            <br>
            <p>The message:</p>
            <p class="cmsg-box"><b><?=htmlspecialchars($item["title"])?></b></p>
            <p class="cmsg-box"><?=str_replace("\n", "<br>", $item["text"])?></p>
            <?php
            if ($item["status"] == 1) { ?>
                <a href="?id=<?=$id?>&statusupdate=2"><button class="btn">Mark as read</button></a>
            <?php }
            if ($item["status"] == 2) { ?>
                <a href="?id=<?=$id?>&statusupdate=1"><button class="btn">Mark as unread</button></a>
            <?php }
            ?>
        </div>
        <?php } else {
        if (isset($_GET["read"])) { ?>
            <p class="text-center"><a href="/responses">Show unread messages</a></p>
        <?php } else { ?>
            <p class="text-center"><a href="/responses?read">Show already read messages</a></p>
        <?php } ?>
        <table class="table1">
            <tr class="table-heading">
                <th>#</th>
                <th>Time</th>
                <th>Name</th>
                <th>Title</th>
            </tr>
            <?php
            if (sizeof($items) == 0) { ?>
            </table>
            <h3 class="text-center">No items to show.</h3>
            <?php } else {
            foreach ($items as $item) {
            $time = date("d.m.Y - G:i", $item["time"]);
            ?>
            <tr class="clickable" data-url="/responses?id=<?=$item["id"]?>">
                <th><?=$item["id"]?></th>
                <td><?=$time?></td>
                <td><?=$item["name"]?></td>
                <td><?=$item["title"]?></td>
            </tr>           
            <?php } ?> </table> <?php } ?>
        <?php } } ?>
        </section>
    </div>
    
<script src="https://static.0r4v3c.xyz/jquery/3.5.1/jquery.min.js"></script>
<script src="https://static.0r4v3c.xyz/cute-alert/cute-alert.js"></script>
<script src="/assets/js/main.js"></script>
<script src="/assets/js/contact.js"></script>
<script>
$("#main").fadeIn(100);
</script>
</body>
</html>