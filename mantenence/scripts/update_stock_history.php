<?php
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
include_once '../../class.PartLocation.php';
include_once '../../class.User.php';
include_once '../../class.InventoryAudit.php';
include_once '../../class.Warehouse.php';

error_reporting(E_ALL);
error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );


if (!$con) {print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$corporate_currency='GBP';


//$from=date("Y-m-d",strtotime('now -2000 day'));
//$from=date("Y-m-d");

$from='2006-04-07';

$to=date("Y-m-d",strtotime('now -1 day'));


$from=date("Y-m-d",strtotime('now'));

$to=date("Y-m-d",strtotime('now'));


//$from=date("Y-m-d");
//$to='2013-09-05';


$warehouse=new Warehouse(1);

$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s order by `Date` desc",
	prepare_mysql($from),prepare_mysql($to));
$res=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_array($res)) {
$where=' `Part SKU`=46433';
	

$where='  true';
	$sql=sprintf('select `Part SKU` from `Part Dimension` where %s     ',$where);
	$res2=mysql_query($sql);
	$count=0;
	while ($row2=mysql_fetch_array($res2)) {
		//print "\t\t\t\tChecking:".$row2['Part SKU']."\r";
		$sql=sprintf("select `Location Key`  from `Inventory Transaction Fact` where  `Inventory Transaction Type` like 'Associate' and  `Part SKU`=%d and `Date`<=%s group by `Location Key`",
			$row2['Part SKU'],
			prepare_mysql($row['Date'].' 23:59:59')
		);

		$result=mysql_query($sql);
		
		while ($row3=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		
			$part_location=new PartLocation($row2['Part SKU'].'_'.$row3['Location Key']);



			$part_location->update_stock_history_date($row['Date']);

			print $row['Date'].' '.$row2['Part SKU'].'_'.$row3['Location Key']."\r";

		}



	}

	
	
	$warehouse->update_inventory_snapshot($row['Date']);
}









?>
