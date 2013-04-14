<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2013 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Warehouse.php';

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


$sql="select * from `Location Dimension`  ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$sql=sprintf("update `Location Dimension` set `Warehouse Flag Key`=%s where `Location Key`=%d",
		prepare_mysql(get_flag_key($row['Warehouse Flag'])),
		$row['Location Key']

	);

	mysql_query($sql);
}


function get_flag_key($color) {
	$sql=sprintf("select `Warehouse Flag Key` from  `Warehouse Flag Dimension` where `Warehouse Flag Color`=%s",
		prepare_mysql($color));

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		return $row['Warehouse Flag Key'];
	}else {
		return '';
	}

}


?>
