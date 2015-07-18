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
    die('<center>请执行安装/修复程序</center>');
}
?>
</head>

<body>
   <form action="index.php" method="post" name="index">
       <center>您的ID：<input type="text" name="name" size="10"></input></center>
<?php

$link = mysql_connect(DB,UNAME,PWD);
if(!$link){
    die("<center>出现错误，错误代码R01</center>");
}
    $db_selected = mysql_select_db(DB_NAME,$link);
    if(!$db_selected){
        die('<center>出现错误，错误代码:R02</center>');
    }
    
    $vistorip = $_SERVER["REMOTE_ADDR"];
    $result = mysql_query("SELECT MAX(id) FROM ".TABLE_PREFIX."_questions");
    if(!$result){
        die("出现错误，错误代码:R04");
    }
    $typresult;
    $queresult;
 for($rows_count=0;$rows_count<1;$rows_count++){ 
 $qcount = mysql_result($result,$rows_count,"MAX(id)");
 }
 
    for ($id=1;$id<=$qcount;$id++){
        $tresult = mysql_query("SELECT type FROM ".TABLE_PREFIX."_questions LIMIT ".$id);
        if(!$tresult){
          die("出现错误，错误代码:R04");
        }  
        $qresult = mysql_query("SELECT question FROM ".TABLE_PREFIX."_questions LIMIT ".$id);
        if(!$qresult){
          die("出现错误，错误代码:R04");
        }
        $typresult = mysql_result($tresult,0,"type");   
        $queresult = mysql_result($qresult,0,"question");
    if($typresult==1){
        echo '<center>'.$queresult.':<input type="text" name="q'.$id.'"></input></center>';
    }
    if($typresult==2){
        /* 待完成 */
        echo '<center>'.$queresult.':<input type="radio" name="q'.$id.'"></input></center>';
    }    
    if($typresult==3){
        /* 待完成 */
        echo '<center>'.$queresult.':<input type="checkbox" name="q'.$id.'"></input></center>';
    }    
 }
        echo "<center>您的IP:".$vistorip."已被记录";

if(isset($_POST["name"])){
    $idresult = mysql_query("SELECT MAX(id) FROM ".TABLE_PREFIX."_applicant");
        if(!$idresult){
          die("出现错误，错误代码:R04");
        }
    $id = mysql_result($tresult,0,"id");
    $nowid =$id+1;
    $name = htmlspecialchars($_POST["name"]);
    mysql_query("INSERT INTO ".TABLE_PREFIX.'_applicant(`id`,`name`,`ips`) VALUES('.$nowid.','.$name.','.$vistorip.')')or die("<center>出现错误，错误代码:R04</center>");
    $qresult = mysql_query("SELECT question FROM ".TABLE_PREFIX."_questions LIMIT ".$id);
    mysql_close();
}
?>
<center><input type="submit" value="提交"></input></center>
<form>
</body>
</html>