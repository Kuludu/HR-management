<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php
$path = dirname(__FILE__);
include $path.'\setting.php';
//error_reporting(0);
if(OGNAME != null){
    echo '<title>'.OGNAME.'人事管理</title>';
}else{
    echo '<center>请执行安装/修复程序</center>';
}
?>
</head>

<body>
   <form action="index.php" method="post" name="index">
       <center>您的ID：<input type="text" name="name" size="10"></input></center>
<?php
if(isset($_POST["name"])){
$link = mysql_connect(DB,UNAME,PWD);
if($link){
    $db_selected = mysql_select_db(DB_NAME,$link);
    if(!$db_selected){
        die('<center>出现错误，错误代码:R02</center>');
    }
    $name = htmlspecialchars($_POST["name"]);
    $vistorip = $_SERVER["REMOTE_ADDR"];
    echo "<center>您的IP".$vistorip."已被记录";
}else{
    die("<center>出现错误，错误代码:R01</center>");
}
mysql_close();
}
?>
<center><input type="submit" value="提交"></input></center>
<form>
</body>
</html>