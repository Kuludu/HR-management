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
$file = '<?php define("OGNAME","'.$rec_ogname.'"); define("DB","'.$rec_db.'"); define("DB_NAME","'.$rec_db_name.'"); define("UNAME","'.$rec_uname.'"); define("PWD","'.$rec_pwd.'"); define("TABLE_PREFIX","'.$rec_table_prefix.'"); ?>';
$f_open = fopen("setting.php","w");
fwrite($f_open,$file);
fclose($f_open);
   /*数据库始化*/
include './setting.php';
$link = mysql_connect(DB,UNAME,PWD);
if($link){
  $db_selecte = mysql_query(DB_NAME,$link);	
  if($db_selected){
      mysql_db_query($dbname, "CREATE TABLE IF NOT EXITS ".TABLE_PREFIX."_applicant")or die("出现错误，错误代码:I03");
      mysql_db_query($dbname, "CREATE TABLE IF NOT EXITS ".TABLE_PREFIX."_questions")or die("出现错误，错误代码:I03");
      mysql_db_query($dbname, "INSERT INTO ".TABLE_PREFIX.'_questions(q1) VALUES("默认问题")')or die("出现错误，错误代码:I03");
      mysql_db_query($dbname, "INSERT INTO ".TABLE_PREFIX.'_questions(q1) VALUES("单选")')or die("出现错误，错误代码:I03");
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