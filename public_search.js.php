<?php
include_once 'app_files/db/dns.php';

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	exit;
}

if (!isset($_REQUEST['id'])) {
	$id=-1;
} else
	$id=$_REQUEST['id'];
header('Content-type: text/javascript');
$sql=sprintf("select `Site Search Javascript` from `Site Dimension` where `Site Key`=%d",$id);
$result = mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
	print $row['Site Search Javascript'];
}

?>
