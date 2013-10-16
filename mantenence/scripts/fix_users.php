<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Staff.php';

include_once '../../class.User.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');


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



require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;


// FOr carlos delete next exit;

$sql=sprintf("select * from `User Dimension` where `User Type` in ('Staff','Administrator')");
$result1=mysql_query($sql);
while ($row1=mysql_fetch_array($result1, MYSQL_ASSOC)   ) {
	$user=new User($row1['User Key']);
	
	$sql=sprintf("insert into `User Staff Settings Dimension` (`User Key`,`User Theme Key`,`User Theme Background Key`,`User Dashboard Key`) values (%d,%d,%d,%s)  ",
	$user->id,
	$row1['User Theme Key'],
	$row1['User Theme Background Key'],
	prepare_mysql($row1['User Dashboard Key'])
	
	);
	mysql_query($sql);
	
	//$user->update_staff_type();

}




?>
