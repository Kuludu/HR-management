<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php
$path = dirname(__FILE__);
include $path.'/api.php';
api::start();
?>
</head>

<body>
   <form action="applicant.php" method="post" name="index">
       <center>您的ID：<input type="text" name="name" size="10"></input></center>

<?php    
    $vistorip = $_SERVER["REMOTE_ADDR"];
    $qcount = api::mysql_query("SELECT MAX(id) FROM ".TABLE_PREFIX."_questions","MAX(id)",false);
    /*
     * 问题类型未完成
     */
 api::print_question();
 ?>
<center>验证码：<input type="text" class="input" name="code_num"/>  
<img src="validatecode.php" title="看不清，点击换一张" onclick="this.src='validatecode.php?'+Math.random();"></center> 
<center><input type="submit" value="提交"></input></center>
<form>
 <?php
api::chk_code();
  if(!empty($_POST["name"])){
    $icount = api::mysql_query("SELECT MAX(id) FROM ".TABLE_PREFIX."_applicant","MAX(id)",false);
    $nowid = $icount+1;
    $name = htmlspecialchars($_POST["name"]);
    api::mysql_query("INSERT INTO ".TABLE_PREFIX.'_applicant(`id`,`name`,`ips`,`pass`) VALUES('.$nowid.',"'.$name.'","'.$vistorip.'","false")',"",true);
    
    $answers = array();
    for($id=1;$id<=$qcount;$id++){
        $answer = htmlspecialchars($_POST["q".$id]);
        $question = api::mysql_query("SELECT question FROM ".TABLE_PREFIX."_questions WHERE id=".$id,"question",false);
        $data = '<center>问题:'.$question.'       回答：'.$answer.'</center></br>';
        $answers[$id] = $data;
    }
    $word = implode("",$answers);
    $file = '<?php echo "'.$word.'"; ?>';
    $f_open = fopen($path."/applicant/".$nowid.".php","w");
    fwrite($f_open,$file);
    fclose($f_open);
    /*
     * 文件安全访问未完成
     */
    echo '<script>alert("成功提交申请");</script>';
    die('<center>提交成功，请等待审核</center>');   
  }
?>
</body>
</html>