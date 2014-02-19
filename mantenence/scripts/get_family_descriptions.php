<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Deal.php';
include_once '../../class.DealCampaign.php';

include_once '../../class.Charge.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Warehouse.php';
include_once '../../class.Node.php';
include_once '../../class.Shipping.php';
include_once '../../class.SupplierProduct.php';


error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$csv_file='test1A.csv';
$handle_csv = fopen($csv_file, "r");

$date=gmdate("Y-m-d H:i:s");
$editor=array(
	'Date'=>$date,
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>0,
	'User Key'=>0,
);
$store=new Store('code','UK');



while (($_cols = fgetcsv($handle_csv))!== false) {
	//print_r($_cols);
	


	$family_code=$_cols[0];
	$family_description=_trim($_cols[1].$_cols[2].$_cols[3].$_cols[4].$_cols[5]);

	$family=new Family('code_store',$family_code,$store->id);

	if($family->id){
		$family->update_field_switcher('Product Family Description',$family_description);
	}


}



?>
