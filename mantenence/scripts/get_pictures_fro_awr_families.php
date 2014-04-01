<?php
date_default_timezone_set('UTC');

include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Image.php';

include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';


$sql=sprintf("select `Product Family Key`,`Product Family Code`,`Product Family Store Key`,`Product Family Main Image Key` from `Product Family Dimension`  ");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

	if ($row['Product Family Store Key']!=1 and !$row['Product Family Main Image Key']) {
		$family=new Family($row['Product Family Key']);
		$family_uk=new Family('code_store',$row['Product Family Code'],1);
		if ($family_uk->id and $family_uk->data['Product Family Main Image Key']) {
			$family->add_image($family_uk->data['Product Family Main Image Key']);
			$family->update_main_image($family_uk->data['Product Family Main Image Key']);
		}

	}


}



?>
