<?php
include_once('app_files/db/dns.php');
include_once('class.Image.php');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}



if(!isset($_REQUEST['theme_key'])){
  $theme_key=1;
}else
  $theme_key=$_REQUEST['theme_key'];

if(!isset($_REQUEST['theme_background_key'])){
  $theme_background_key=0;
}else
  $theme_background_key=$_REQUEST['theme_background_key'];

header("Content-type: text/css");


$sql=sprintf("select `Theme CSS Buttons`,`Theme CSS Header`,`Theme CSS Tables` ,`Theme CSS Top Navigation`from `Theme Dimension` where `Theme Key`=%d",$theme_key);
$res=mysql_query($sql);

if ($row=mysql_fetch_assoc($res)) {
    print $row['Theme CSS Buttons'];
    print $row['Theme CSS Header'];
    print $row['Theme CSS Tables'];;
    print $row['Theme CSS Top Navigation'];
}

$sql=sprintf("select `Header CSS`,`Background CSS`,`Footer CSS` from `Theme Background Dimension` where `Theme Background Key`=%d",$theme_background_key);
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
    print $row['Header CSS'];
    print $row['Background CSS'];
    print $row['Footer CSS'];
}


?>
