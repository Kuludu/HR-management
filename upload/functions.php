<?php

class functions {

    //只能输出第一行的结果！
    public static function mysql_query($query, $result_name, $over) {
        /*
         * 此处无需多次连接
         */
        $link = mysql_connect(DB, UNAME, PWD);
        if (!$link) {
            die("<center>出现错误，错误代码:D01</center>");
        }

        $db_selected = mysql_select_db(DB_NAME, $link);
        if (!$db_selected) {
            die('<center>出现错误，错误代码:D02</center>');
        }

        $temp_result = mysql_query($query);
        if (!empty($result_name)) {
            if (!$temp_result) {
                die("<center>出现错误，错误代码:D03</center>" . mysql_error());
            }
            $result = mysql_result($temp_result, 0, $result_name);
            if ($over == true) {
                mysql_close();
            }
            return $result;
        } else {
            if ($over == true) {
                mysql_close();
            }
        }
    }

    public static function start() {
        $lifeTime = 2 * 3600;
        session_set_cookie_params($lifeTime);
        session_start();
        $path = dirname(__FILE__);
        require $path . '\setting.php';
        if (OGNAME != null) {
            echo '<title>' . OGNAME . '人事管理</title>';
        } else {
            die('<center>出现错误，错误代码R01</center>');
        }
    }

    public static function chk_code() {
        if (!empty($_POST["code_num"])) {
            $code_num = htmlspecialchars($_POST["code_num"]);
            if ($code_num != $_SESSION["rand"]) {
                die("<center>验证码错误！</center>");
            }
        }
    }

    public static function print_question() {
        $qcount = self::mysql_query("SELECT MAX(id) FROM " . TABLE_PREFIX . "_questions", "MAX(id)", false);
        for ($id = 1; $id <= $qcount; $id++) {
            $typeresult = self::mysql_query("SELECT type FROM " . TABLE_PREFIX . "_questions WHERE id=" . $id, "type", false);
            $quesresult = self::mysql_query("SELECT question FROM " . TABLE_PREFIX . "_questions WHERE id=" . $id, "question", false);
            if ($typeresult == 1) {
                echo '<center>' . $quesresult . ':<input type="text" name="q' . $id . '"></input></center>';
            }
            if ($typeresult == 2) {
                /* 待完成 */
                echo '<center>' . $quesresult . ':<input type="radio" name="q' . $id . '"></input></center>';
            }
            if ($typeresult == 3) {
                /* 待完成 */
                echo '<center>' . $quesresult . ':<input type="checkbox" name="q' . $id . '"></input></center>';
            }
        }
    }

    public static function print_form($do, $name, $stuff) {
        echo '<form action="' . $do . '" method="post" name="' . $name . '">';
        echo $stuff;
        echo '<center><input type="submit" name="submit"/></center>';
        echo "</form>";
    }

    public static function edit_question() {

        echo '<center>修改问题</center>';
        $qcount = self::mysql_query("SELECT MAX(id) FROM " . TABLE_PREFIX . "_questions", "MAX(id)", false);
        if (!empty($_POST["added_question"])) {
            $add_question = htmlspecialchars($_POST["added_question"]);
            $id = $qcount + 1;
            self::mysql_query('INSERT INTO ' . TABLE_PREFIX . '_questions(`id`,`type`,`question`) VALUES(' . $id . ',1,"' . $add_question . '")', "", false);
            header('Location: admin.php');
        }
        for ($id = 1; $id <= $qcount; $id++) {
            if (!empty($_POST[$id])) {
                $post_question = htmlspecialchars($_POST[$id]);
                self::mysql_query('UPDATE ' . TABLE_PREFIX . '_questions SET question="' . $post_question . '" WHERE id=' . $id, "", false);
            }
            $typeresult = self::mysql_query("SELECT type FROM " . TABLE_PREFIX . "_questions WHERE id=" . $id, "type", false);
            $quesresult = self::mysql_query("SELECT question FROM " . TABLE_PREFIX . "_questions WHERE id=" . $id, "question", false);
            /*
             * 问题类型尚未完成
             */
            self::print_form("admin.php", "question_edit", '<center>问题：' . $id . '<input type="text" name="' . $id . '" value="' . $quesresult . '"></input></center>');
        }
        echo '<center>增加问题</center>';
        self::print_form("admin.php", "question_add", '<center>问题：<input type="text" name="added_question"></input></center>');
    }

    public static function chk_form() {
        /*
         * 待完成
         */
    }

    public static function register_session($name) {
        if (empty($_SESSION[$name]) || $_SESSION[$name] == false) {
            $_SESSION[$name] = true;
        }
    }

    public static function is_logined($name) {
        if (!empty($_SESSION[$name])) {
            if ($_SESSION[$name] == true) {
                return true;
            }
        }
    }

    public static function unregister_session($name) {
        $_SESSION[$name] = false;
    }

    public static function admin() {

        $admresult = self::mysql_query("SELECT admin FROM " . TABLE_PREFIX . "_setting", "admin", false);
        if (!empty($_POST["quit"])) {
            if ($_POST["quit"] === "true") {
                functions::unregister_session($admresult);
            }
        }

        $lastip = self::mysql_query("SELECT admin_last_login FROM " . TABLE_PREFIX . "_setting", "admin_last_login", false);

        if (!empty($lastip)) {
            echo "<center>欢迎你的访问" . $admresult . "，上次登陆IP：" . $lastip . "</center></br>";
        } else {
            echo "<center>欢迎你的访问" . $admresult . "，您是第一次登陆后台!</center></br>";
        }
        $newip = $_SERVER["REMOTE_ADDR"];
        self::mysql_query('UPDATE ' . TABLE_PREFIX . '_setting SET admin_last_login="' . $newip . '"', "", false);

        $acount = self::mysql_query("SELECT MAX(id) FROM " . TABLE_PREFIX . "_applicant", "MAX(id)", false);

        for ($uid = 1; $uid <= $acount; $uid++) {
            $name = self::mysql_query("SELECT name FROM " . TABLE_PREFIX . "_applicant WHERE id=" . $uid, "name", false);

            if (!empty($_POST["p" . $uid]) && $_POST["p" . $uid] == true) {
                self::mysql_query('UPDATE ' . TABLE_PREFIX . '_applicant SET pass="true" WHERE id=' . $uid, "", false);
                $rand_pwd = self::get_rand_code();
                self::mysql_query('INSERT INTO ' . TABLE_PREFIX . '_members(`id`,`access_level`,`uname`,`pwd`) VALUES(' . $uid . ',1,"' . $name . '","' . $rand_pwd . '")', "", false);
                echo '<center>用户随机密码为:' . $rand_pwd . '</center>';
            }

            $ip = self::mysql_query("SELECT ips FROM " . TABLE_PREFIX . "_applicant WHERE id=" . $uid, "ips", false);
            $is_passed = self::mysql_query("SELECT pass FROM " . TABLE_PREFIX . "_applicant WHERE id=" . $uid, "pass", false);

            echo '<center>注册序号' . $uid . '  成员ID:' . $name . "，注册IP：" . $ip . "</center>";
            include dirname(__FILE__) . '/applicant/' . $uid . '.php';
            if ($is_passed === "true") {
                echo "<center>已通过审核!</center></br>";
            } else {
                echo "<center>是否审核通过?</center></br>";
                self::print_form("admin.php", $uid, '<center><input name="p' . $uid . '" type="radio" value="agree" />同意</center>');
            }
        }

        self::edit_question();
        echo "</br><center>登出？</center></br>";
        self::print_form("admin.php", $uid, '<center><input name="quit" type="radio" value="true" />是的</center>');
    }

    public static function get_rand_code() {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $len = strlen($str) - 1;
        $rand_code = '';
        for ($i = 0; $i < 16; $i++) {
            $num = mt_rand(0, $len);
            $rand_code .= $str[$num];
        }
        return $rand_code;
    }

    public static function is_registered($name) {
        /*
         * 检测重复注册
         */
    }

}
