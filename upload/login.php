<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php
//error_reporting(0);
$path = dirname(__FILE__);
include $path.'/api.php';
api::start();
?>
</head>
<body>
<center>成员登陆管理</center>
<form action="login.php" method="post" name="admin">
    <center>请输入用户名：<input type="text" name="uname" size="10"/></center>
    <center>请输入密码：<input type="password" name="pwd" size="10"/></center>
    <center>验证码：<input type="text" class="input" name="code_num"/>
<img src="validatecode.php" title="看不清，点击换一张" onclick="this.src='validatecode.php?'+Math.random();"></center> 
    <center><input type="submit" name="submit"/></center>
</form>
<?php
api::chk_code();
?>
</body>
</html>