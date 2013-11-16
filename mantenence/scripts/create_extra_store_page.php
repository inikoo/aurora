<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Site.php';
include_once '../../class.Image.php';
include_once '../../class.Site.php';

include_once '../../class.Page.php';
include_once '../../class.Store.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant2';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

global $myconf;



$pages_data=array(array(
	'Page Code'=>'search',
	'Page Section'=>'search',
	'Page Store Content Display Type'=>'Template',
	'Page Source Template'=>'search.tpl',
	'Page Store Content Template Filename'=>'search.tpl',
	'Page URL'=>'search.php',
	'Page Description'=>'Search',
	'Page Store Section'=>'Search',
	'Page Title'=>'Search',
	'Page Short Title'=>'Search',
	'Page Store Title'=>'Search',
	'Page Store Subtitle'=>'',
	'Page Store Slogan'=>'Search our webpage',
	'Page Store Resume'=>'Search our webpage'
)

);






$sql=sprintf("select `Site Key` from `Site Dimension` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {

	$site=new Site($row['Site Key']);

	foreach ($pages_data as $page_data) {
		$page_data['Page Store Order Template']='No Applicable';

		$page_data['Page Store Creation Date']=gmdate('Y-m-d H:i:s');
		$page_data['Page Store Last Update Date']=gmdate('Y-m-d H:i:s');
		$page_data['Page Store Last Structural Change Date']=gmdate('Y-m-d H:i:s');
		$page_data['Page Type']='Store';
		$page_data['Page Store Source Type'] ='Static';



		$site->add_store_page($page_data);

	}


}




?>
