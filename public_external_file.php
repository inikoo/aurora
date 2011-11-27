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

if(!isset($_REQUEST['id'])){
  $id=-1;
}else
  $id=$_REQUEST['id'];
 
 
 
$sql=sprintf("select `Page Store External File Type`,`Page Store External File Content` from `Page Store External File Dimension` where `Page Store External File Key`=%d",$id);
$result = mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
if($row['Page Store External File Type']=='CSS')
header('Content-type: text/css');
else{
header('Content-type: text/javascript');
}
    print $row['Page Store External File Content'];


}
  
?>