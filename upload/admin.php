<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <?php
        $path = dirname(__FILE__);
        include $path . '/functions.php';
        functions::start();
        ?>
    </head>
    <body>
        <?php
        $admresult = functions::mysql_query("SELECT admin FROM " . TABLE_PREFIX . "_setting", "admin", false);
        if (!functions::is_logined($admresult) == true) {
            echo '<center>此页面为管理界面，非管理人员请离开！</center>';
        } else {
            functions::admin();
        }
        ?>
    </body>
</html>