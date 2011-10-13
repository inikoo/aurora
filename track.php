<?php
/*
 File: track.php
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Copyright (c) 2010, Inikoo
 Version 2.0
*/

//exit;
require_once 'app_files/db/dns.php';



$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
 header('Content-Type: image/png');
readfile('art/inikoo_footer_for_emails.png');
exit;
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
  header('Content-Type: image/png');
readfile('art/inikoo_footer_for_emails.png');
exit;
}
mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
mysql_query("SET time_zone='+0:00'");


if(!isset($_REQUEST['sendkey']) or !is_numeric($_REQUEST['sendkey'])){

	  header('Content-Type: image/png');
readfile('art/inikoo_footer_for_emails.png');
}

//print $_GET['sendkey'];

$sendkey = trim($_REQUEST['sendkey']);
$sql="SELECT `Email Send Type Key` , `Email Key`, `Email Send First Read Date`, `Email Send Last Read Date`, `Email Send Number Reads` FROM `Email Send Dimension` WHERE `Email Send Key` = '$sendkey'";

$result=mysql_query($sql);


$r = mysql_fetch_array($result);
//print_r($r);
$email_key=$r['Email Key'];
$first_date=$r['Email Send First Read Date'];
$last_date=$r['Email Send Last Read Date'];
$send_number_reads=$r['Email Send Number Reads'];
$campaign_key = $r['Email Send Type Key'];

if($first_date == NULL){

	$update_sql = "UPDATE `Email Send Dimension` SET `Email Send First Read Date` = NOW(), `Email Send Last Read Date` = NOW(), `Email Send Number Reads` = '1' WHERE `Email Send Key` ='$sendkey' AND `Email Send First Read Date` IS NULL AND `Email Send Last Read Date` IS NULL AND `Email Send Number Reads` IS NULL";

	print $update_sql;
	
	
	//$update_master_sql = "UPDATE `Email Campaign Dimension` SET `Number of Read Emails` = `Number of Read Emails`+1 WHERE `Email Campaign Key` = '$campaign_key'";
	//echo $update_master_sql;
	$update_query=mysql_query($update_sql);
	//$update_query2=mysql_query($update_master_sql);
	}else{
	$update_sql = "UPDATE `Email Send Dimension` SET `Email Send Last Read Date` = NOW(), `Email Send Number Reads` = `Email Send Number Reads`+1 WHERE `Email Key` ='$sendkey' AND `Email Send First Read Date` IS NOT NULL AND `Email Send Last Read Date` IS NOT NULL AND `Email Send Number Reads` IS NOT NULL";

	$update_query=mysql_query($update_sql);

	}

?>
