<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Attachment.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql="select * from `Attachment Bridge` ";
$count=1;
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$sql=sprintf("update `Attachment Bridge` set `Attachment Bridge Key`=%d where `Attachment key`=%d and `Subject`=%s and `Subject Key`=%d",
		$count,
		$row['Attachment Key'],
		prepare_mysql($row['Subject']),
		$row['Subject Key']

	);
	
	mysql_query($sql);
	$count++;

}



$sql="select * from `Attachment Dimension` ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$sql=sprintf("update `Attachment Bridge` set `Attachment Caption`=%s,`Attachment File Original Name`=%s where `Attachment key`=%d",
		prepare_mysql($row['Attachment Caption'],false),
		prepare_mysql($row['Attachment File Original Name']),
		$row['Attachment Key']

	);
	
	mysql_query($sql);

}


?>
