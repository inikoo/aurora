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



$sql="select * from `Product Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$product=new Product('pid',$row['Product ID']);

	if ($product->data['Product Availability Type']=='Discontinued' or $product->data['Product Sales Type']!='Public Sale' or $product->data['Product Record Type']=='Historic'  ) {
		$_state='Offline';
	}else {
		$_state='Online';
	}



	foreach ($product->get_pages_keys() as $page_key) {


		$page=new Page($page_key);
		$page->update(array('Page State'=>$_state),'no_history');
	}




}

$sql="select * from `Product Family Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$family=new Family($row['Product Family Key']);

	$family->update_web_state();

}




$sql="select * from `Page Store Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$page=new Page($row['Page Key']);

	if ($page->data['Page Store Section']=='Product Description') {
		$product=new Product('pid',$page->data['Page Parent Key']);
		$page->update_number_found_in();


		if ($page->data['Number Found In Links']==0) {


			$family=new Family($product->data['Product Family Key']);

			if ($family->id) {


				foreach ($family->get_pages_keys() as $_page_key) {
					$_page=new Page($_page_key);

					if ($_page->data['Page State']=='Online') {
						$page->add_found_in_link($_page->id);
						break;
					}
				}

			}
			else {


			}
		}



		$page->update(array(
				'Page Store Title'=>$product->data['Product Name'],
				'Page Parent Code'=>$product->data['Product Code'],
			),'no_history');

	}
}






?>
