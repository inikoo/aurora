<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Site.php';

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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql="select * from `Page Store Dimension` where `Page Key`=6079";
$sql="select * from `Page Store Dimension` order by `Page Key` desc";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$page=new Page($row['Page Key']);
	$site=new Site($page->data['Page Site Key']);
	$page->update_see_also();


	switch ($page->data['Page Store Section']) {
	case 'Product Description':
		$product=new Product('pid',$page->data['Page Parent Key']);
		$title=$product->data['Product Code'].', '.$product->data['Product Name'].', '.$site->data['Site Name'];
		if ($page->data['Page Type']=='Store' and $page->data['Page Store Content Display Type']=='Template') {
			$page->update_field_switcher('Page Title',$title);
						$page->update_field_switcher('Page Store Title',$product->data['Product Name']);

		}
		break;
	case 'Family Catalogue':
		$family=new Family($page->data['Page Parent Key']);
		$title=$family->data['Product Family Code'].', '.$family->data['Product Family Name'].', '.$site->data['Site Name'];
		// print $title;
		if ($page->data['Page Type']=='Store' and $page->data['Page Store Content Display Type']=='Template') {
			
			$page->update_field_switcher('Page Title',$title);
			$page->update_field_switcher('Page Store Title',$family->data['Product Family Name']);

		}
		break;
	case 'Department Catalogue':
		$department=new Department($page->data['Page Parent Key']);
		$title=$department->data['Product Department Name'].', '.$site->data['Site Name'];

		if ($page->data['Page Type']=='Store' and $page->data['Page Store Content Display Type']=='Template') {
			$page->update_field_switcher('Page Title',$title);
			$page->update_field_switcher('Page Store Title',$department->data['Product Department Name']);

		}

		break;
	}


	$page->update_store_search();

}


?>
