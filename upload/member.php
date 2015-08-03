<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <?php
        $path = dirname(__FILE__);
        include $path . '/functions.php';
        functions::start();
        ?>
    </head>
    <body>
        <?php
        if (!empty($_GET["name"])) {
            $name = htmlspecialchars($_GET["name"]);
            if (functions::is_logined($name) == true) {
                functions::print_form("member.php?name=" . $name, "change_pwd", '<center>更改密码<input type="text" name="pwd" /></center>');

                if (!empty($_POST["pwd"])) {
                    $pwd = htmlspecialchars($_POST["pwd"]);
                    functions::mysql_query('UPDATE ' . TABLE_PREFIX . '_members SET pwd="' . $pwd . '" WHERE uname="' . $name . '"', "", true);
                }
            } else {
                header("Location:login.php");
            }
        } else {
            header("Location:login.php");
        }
        ?>
    </body>
</html>