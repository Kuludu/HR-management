<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php
//error_reporting(0);
        $path = dirname(__FILE__);
        include $path . '/functions.php';
        functions::start();
        ?>
    </head>
    <body>
    <center>成员登陆</center>
    <form action="login.php" method="post" name="admin">
        <center>请输入用户名：<input type="text" name="uname" size="10"/></center>
        <center>请输入密码：<input type="password" name="pwd" size="10"/></center>
        <center>验证码：<input type="text" class="input" name="code_num"/>
            <img src="validatecode.php" title="看不清，点击换一张" onclick="this.src = 'validatecode.php?' + Math.random();"></center> 
        <center><input type="submit" name="submit"/></center>
    </form>
    <?php
    functions::chk_code();
    if (!empty($_POST["uname"]) && !empty($_POST["pwd"])) {
        $name = htmlspecialchars($_POST["uname"]);
        $pwd = htmlspecialchars($_POST["pwd"]);

        $admin = functions::mysql_query("SELECT admin FROM " . TABLE_PREFIX . "_setting", "admin", false);
        if ($name === $admin) {
            $admin_pwd = functions::mysql_query("SELECT safe_code FROM " . TABLE_PREFIX . "_setting", "safe_code", false);
            if ($admin_pwd === $pwd) {
                functions::register_session($name);
                header('Location: admin.php');
                exit();
            } else {
                die("<center>账户或密码错误</center>");
            }
        }

        $is_exit = functions::mysql_query('SELECT count(*) FROM ' . TABLE_PREFIX . '_members WHERE uname="' . $name . '"', "count(*)", false);

        if ($is_exit == 0) {
            die("<center>账户或密码错误</center>");
        } else {
            $rpwd = functions::mysql_query('SELECT pwd FROM ' . TABLE_PREFIX . '_members WHERE uname="' . $name . '"', "pwd", false);
        }

        if ($pwd === $rpwd) {
            functions::register_session($name);
            header('Location: member.php?name=' . $name);
        } else {
            die("<center>账户或密码错误</center>");
        }
    }
    ?>
</body>
</html>