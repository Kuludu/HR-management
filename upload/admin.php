<!doctype html>
<html>
<head>
<meta charset="utf-8">7
<?php
//error_reporting(0);
include './setting.php';
//error_reporting(0);
if(OGNAME != null){
echo '<title>'.OGNAME.'人事管理</title>';
}else{
    echo '请执行安装/修复程序';
}
?>
</head>
<body>
<?php
$link = mysql_connect(DB,UNAME,PWD);
if($link){
    //read
}else{
    die("出现错误，错误代码：A01");
}
?>
</body>
</html>