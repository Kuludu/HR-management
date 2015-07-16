<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>欢迎使用Kuludu人事管理系统安装/修复程序</title>
</head>

<body>
<center><h4>第一步，请输入一下信息（如不清楚，请咨询您的主机提供商）</h4></center>
<form action="install.php" method="post" name="install">
<center>团体名称:<input type="text" name="ogname" size="50"></input></center>
<center>数据库地址:<input type="text" name="db" size="50" value="localhost"></input></center>
<center>数据库名:<input type="text" name="db_name" size="50"></input></center>
<center>数据库用户名:<input type="text" name="uname" size="50"></input></center>
<center>数据库密码:<input type="password" name="pwd" size="50"></input></center>
<center>数据库表前缀（需安装多个管理系统请修改）:<input type="text" name="table_prefix" size="10" value="pm_"></input></center>
<center><input type="submit" value="提交"></input></center>
</form>
<?php
//error_reporting(0);
  /*信息初始化*/
if(isset($_POST["db"])&&isset($_POST["db_name"])&&isset($_POST["uname"])&&isset($_POST["pwd"])&&isset($_POST["table_prefix"])){
	if(file_exists("setting.php")) {
    echo "<center>修复中</center>";
	unlink("setting.php")or die("<center>出现错误，错误代码：I05</center>");
} else {
    echo "<center>安装中</center>";
}
  /*检查信息*/
$rec_ogname = htmlspecialchars($_POST["ogname"]);
$rec_db = htmlspecialchars($_POST["db"]);
$rec_db_name = htmlspecialchars($_POST["db_name"]);
$rec_uname = htmlspecialchars($_POST["uname"]);
$rec_pwd = htmlspecialchars($_POST["pwd"]);
$rec_table_prefix = htmlspecialchars($_POST["table_prefix"]);
 /*生成安全代码*/
$str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$len = strlen($str)-1;
$safe_code = '';
for($i=0;$i<64;$i++){
$num=mt_rand(0,$len);
$safe_code .= $str[$num];
}
 /*写入文件*/
$file = '<?php define("OGNAME","'.$rec_ogname.'"); define("DB","'.$rec_db.'"); define("DB_NAME","'.$rec_db_name.'"); define("UNAME","'.$rec_uname.'"); define("PWD","'.$rec_pwd.'"); define("TABLE_PREFIX","'.$rec_table_prefix.'"); define("SAFE_CODE","'.$safe_code.'"); ?>';
$f_open = fopen("setting.php","w");
fwrite($f_open,$file);
fclose($f_open);
   /*数据库始化*/
$path = dirname(__FILE__);
echo '<center>安装目录：'.$path.'</center>';
include $path.'\setting.php';
$link = mysql_connect(DB,UNAME,PWD);
if($link){
  $db_selected = mysql_select_db(DB_NAME,$link);
  if($db_selected){
      mysql_db_query(DB_NAME, "CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."_applicant(`id` int(10) DEFAULT NULL,"
              . "                                                                    `name` varchar(255) DEFAULT NULL,"
              . "                                                                    `ips` varchar(255) DEFAULT NULL)ENGINE=MyISAM DEFAULT CHARSET=utf8;")or die("<center>出现错误，错误代码:I03</center>".  mysql_error());
      mysql_db_query(DB_NAME, "CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."_questions(`id` int(10) DEFAULT NULL,"
              . "                                                                    `type` int(1) DEAFULT NULL,"
              . "                                                                    `question` varchar(255) DEFAULT NULL)ENGINE=MyISAM DEFAULT CHARSET=utf8;")or die("<center>出现错误，错误代码:I03</center>".  mysql_error());
      mysql_db_query(DB_NAME, "INSERT INTO ".TABLE_PREFIX.'_questions("id","type","question") VALUES("默认问题","1","Not Ready Yet")')or die("<center>出现错误，错误代码:I06</center>");
  }  else {
      die("<center>出现错误，错误代码:I02</center>");
  }
} else {
    die("<center>出现错误，错误代码:I01</center>");
}
echo "<center>程序完成</center>";
unlink("install.php")or die("<center>出现错误，错误代码：I04</center>");
}
?>
</body>
</html>