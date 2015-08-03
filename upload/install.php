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
<center>管理员账户:<input type="text" name="admin" size="50" value="admin"></input></center>
<center>数据库地址:<input type="text" name="db" size="50" value="localhost"></input></center>
<center>数据库名:<input type="text" name="db_name" size="50"></input></center>
<center>数据库用户名:<input type="text" name="uname" size="50"></input></center>
<center>数据库密码:<input type="password" name="pwd" size="50"></input></center>
<center>数据库表前缀（需安装多个管理系统请修改）:<input type="text" name="table_prefix" size="10" value="pm"></input></center>
<center><input type="submit" value="提交"></input></center>
</form>
<?php
error_reporting(0);
  /*信息初始化*/
if(isset($_POST["db"])&&isset($_POST["db_name"])&&isset($_POST["uname"])&&isset($_POST["pwd"])&&isset($_POST["table_prefix"])&&isset($_POST["ogname"])&&isset($_POST["admin"])){
if(file_exists("setting.php")) {
    echo "<center>修复中</center>";
    unlink("setting.php")or die("<center>出现错误，错误代码：I05</center>");
} else {
    echo "<center>安装中</center>";
}
  /*检查信息*/
$rec_ogname = htmlspecialchars($_POST["ogname"]);
$rec_admin = htmlspecialchars($_POST["admin"]);
$rec_db = htmlspecialchars($_POST["db"]);
$rec_db_name = htmlspecialchars($_POST["db_name"]);
$rec_uname = htmlspecialchars($_POST["uname"]);
$rec_pwd = htmlspecialchars($_POST["pwd"]);
$rec_table_prefix = htmlspecialchars($_POST["table_prefix"]);
 /*生成安全代码*/
$str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
$len = strlen($str);
$safe_code = '';
for($i=0;$i<64;$i++){
$num=mt_rand(0,$len)-1;
$safe_code .= $str[$num];
}
 /*写入文件*/
$file = '<?php define("OGNAME","'.$rec_ogname.'"); define("DB","'.$rec_db.'"); define("DB_NAME","'.$rec_db_name.'"); define("UNAME","'.$rec_uname.'"); define("PWD","'.$rec_pwd.'"); define("TABLE_PREFIX","'.$rec_table_prefix.'"); ?>';
$f_open = fopen("setting.php","w");
fwrite($f_open,$file);
fclose($f_open);
echo "<center>您的安全代码是：".$safe_code."</center>";
   /*数据库始化*/
$path = dirname(__FILE__);
echo '<center>安装目录：'.$path.'</center>';
include $path.'\setting.php';
$link = mysql_connect(DB,UNAME,PWD);
if($link){
  $db_selected = mysql_select_db(DB_NAME,$link);
  if($db_selected){
      mysql_query("DROP TABLE IF EXISTS ".TABLE_PREFIX."_applicant,".TABLE_PREFIX."_questions,".TABLE_PREFIX."_setting,".TABLE_PREFIX."_members");
      mysql_query("CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."_applicant(`id` int(10) DEFAULT NULL,"
              . "                                                                    `name` varchar(255) DEFAULT NULL,"
              . "                                                                    `ips` varchar(255) DEFAULT NULL,"
              . "                                                                    `pass` varchar(255) DEFAULT NULL)ENGINE=MyISAM DEFAULT CHARSET=utf8;")or die("<center>出现错误，错误代码:I03</center>".  mysql_error());
      mysql_query("CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."_questions(`id` int(10) DEFAULT NULL,"
              . "                                                                    `type` int(1) DEFAULT NULL,"
              . "                                                                    `question` varchar(255) DEFAULT NULL)ENGINE=MyISAM DEFAULT CHARSET=utf8;")or die("<center>出现错误，错误代码:I03</center>".  mysql_error());
      mysql_query("CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."_members(`id` int(10) DEFAULT NULL,"
              . "                                                                    `access_level` int(1) DEFAULT NULL,"
              . "                                                                    `uname` varchar(255) DEFAULT NULL,"
              . "                                                                    `pwd` varchar(255) DEFAULT NULL)ENGINE=MyISAM DEFAULT CHARSET=utf8;")or die("<center>出现错误，错误代码:I03</center>".  mysql_error());
      mysql_query("CREATE TABLE IF NOT EXISTS ".TABLE_PREFIX."_setting(`safe_code` varchar(64) DEFAULT NULL,"
              . "                                                                    `admin` varchar(255) DEFAULT NULL,"
              . "                                                                    `admin_last_login` varchar(255) DEFAULT NULL)ENGINE=MyISAM DEFAULT CHARSET=utf8;")or die("<center>出现错误，错误代码:I03</center>".  mysql_error());
      mysql_query("INSERT INTO ".TABLE_PREFIX.'_questions(`id`,`type`,`question`) VALUES(1,1,"Not Ready Yet")')or die("<center>出现错误，错误代码:I06</center>");
      mysql_query("INSERT INTO ".TABLE_PREFIX.'_setting(`safe_code`,`admin`) VALUES("'.$safe_code.'","'.$rec_admin.'")')or die("<center>出现错误，错误代码:I06</center>");
  }  else {
      die("<center>出现错误，错误代码:I02</center>");
  }
} else {
      die("<center>出现错误，错误代码:I01</center>");
}
mysql_close();
 /* 创建申请文件夹 */
if(!file_exists("applicant")){
    echo "<center>申请目录未创建</center>";
    $result = mkdir("applicant");
    if(!$result){
        die("出现错误，错误代码:I07");
    }
}
$htaccess = "Deny from all";
$f_open = fopen($path."/applicant/.htaccess","w");
fwrite($f_open,$htaccess);
fclose($f_open);
echo "<center>程序完成</center>";
echo "<center>温馨提示，本页面已经自动删除</center>";
unlink("install.php")or die("<center>出现错误，错误代码：I04</center>");
}
?>
</body>
</html>