<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Site.php';
include_once '../../class.Page.php';

include_once '../../class.SupplierProduct.php';
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


$sql="select * from `Page Dimension` where `Page Type`='Store' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$page=new Page($row['Page Key']);
	
	$page->update_product_totals();

	//$page->update_up_today_requests();
	//$page->update_interval_requests();
	//$page->update_see_also();
/*
	if($page->data['Page Store Section']=='Family Catalogue'){

		$page->update(
		array(
		'Page Store See Also Type'=>'Auto',
		'Number See Also Links'=>5
		)
		);
		
		//$page->update_see_also();
		
	}
	*/
	
}


?>
