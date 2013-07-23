<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Site.php';
include_once '../../class.Sitemap.php';

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




$sql="select * from `Site Dimension` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$site=new Site($row['Site Key']);
	
	if($site->data['Site Sitemap Last Update']){
		$last_update_time=date('U',strtotime($site->data['Site Sitemap Last Update']));
		if($site->data['Site Sitemap Last Ping Google']=='' or 
		($last_update_time-date('U',strtotime($site->data['Site Sitemap Last Ping Google']))>3600)
		){
			$site->ping_sitemap();
		}
	
	
	}
	
}


?>
