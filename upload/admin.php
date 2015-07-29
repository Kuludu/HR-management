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
<?php
$admresult = api::mysql_query("SELECT admin FROM ".TABLE_PREFIX."_setting","admin",false);
if(!api::is_logined($admresult)==true){
echo '<center>此页面为管理界面，非管理人员请离开！</center>
    <form action="admin.php" method="post" name="admin">
    <center>请输入管理账户：<input type="test" name="uname" size="10"></input></center>
    <center>请输入安全代码：<input type="password" name="safe_code" size="64"></input></center>
    <center>验证码：<input type="text" class="input" name="code_num"/>  
<img src="validatecode.php" title="看不清，点击换一张" onclick="this.src="validatecode.php?"+Math.random();"></center> 
    <center><input type="submit" name="submit"></input></center>
</form>';
api::chk_code();
$safresult = api::mysql_query("SELECT safe_code FROM ".TABLE_PREFIX."_setting","safe_code",false);
if(!empty($_POST["uname"])&&!empty($_POST["safe_code"])){
      $admin = htmlspecialchars($_POST["uname"]);
      if($admresult!=$admin){
          die("<center>管理账户或安全代码错误！</center>");
      }
  
      $safecode = htmlspecialchars($_POST["safe_code"]);
      if($safecode!=$safresult){
          die("<center>管理账户或安全代码错误！</center>");
      }
  api:: register_session($admin);
  echo '<script language="JavaScript">window.location.reload();</script>';
  }
}else{
  api::admin();
 }   
 
  

?>
</body>
</html>