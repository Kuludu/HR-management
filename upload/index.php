<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php
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
    $db_selected = mysql_query(DB_NAME,$link);
    if(!$db_selected){
        die('出现错误，错误代码:R02');
    }
    $ch = curl_init();
    $vistorip = $_SERVER["REMOTE_ADDR"];
    $url = 'http://apis.baidu.com/apistore/iplookupservice/iplookup?ip='.$vistorip;
    $header = array('8e941d3974e7d0a85689db9d937fb54e');
    curl_setopt($ch, CURLOPT_HTTPHEADER  , $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch , CURLOPT_URL , $url);
    $res = curl_exec($ch);
    var_dump(json_decode($res));
}else{
    die("出现错误，错误代码:R01");
}
?>
</body>
</html>