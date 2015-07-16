<!doctype html>
<html>
<head>
<meta charset="utf-8">7
<?php
//error_reporting(0);
$path = dirname(__FILE__);
include $path.'\setting.php';
if(OGNAME != null){
echo '<title>'.OGNAME.'人事管理</title>';
}else{
    echo '<center>请执行安装/修复程序</center>';
}
?>
</head>
<body>
<?php
$link = mysql_connect(DB,UNAME,PWD);
if($link){
    //read
}else{
    die("<center>出现错误，错误代码：A01</center>");
}
?>
</body>
</html>