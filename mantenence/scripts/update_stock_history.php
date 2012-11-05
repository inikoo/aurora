<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
include_once '../../class.PartLocation.php';
include_once '../../class.User.php';
include_once '../../class.InventoryAudit.php';

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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$corporate_currency='GBP';


$from=date("Y-m-d",strtotime('now -3500 day'));
//$from=date("Y-m-d");
$to=date("Y-m-d");

//$from='2009-01-01';
//$from=date("Y-m-d");
//$to='2009-01-01';

$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s order by `Date` desc",
	prepare_mysql($from),prepare_mysql($to));
$res=mysql_query($sql);

while ($row=mysql_fetch_array($res)) {
	//print $row['Date']."\r";
	//$where=' `Part SKU`=1629';
	$where='  true';
	$sql=sprintf('select `Part SKU` from `Part Dimension` where %s     ',$where);

	//print $sql;
	$res2=mysql_query($sql);
	$count=0;
	while ($row2=mysql_fetch_array($res2)) {
		//print "\t\t\t\tChecking:".$row2['Part SKU']."\r";
		$sql=sprintf("select `Location Key`  from `Inventory Transaction Fact` where  `Inventory Transaction Type`='Associate' and  `Part SKU`=%d and `Date`<=%s group by `Location Key`",
			$row2['Part SKU'],
			prepare_mysql($row['Date'])
		);
	
		$result=mysql_query($sql);
		$_locations=array();
		while ($row3=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			if (in_array($row3['Location Key'],$_locations)) {
				continue;
			}else {
				$_locations[]=$row3['Location Key'];
			}

			$part_location=new PartLocation($row2['Part SKU'].'_'.$row3['Location Key']);

			

			$part_location->update_stock_history_date($row['Date']);
			
			print $row['Date'].' '.$row2['Part SKU'].'_'.$row3['Location Key']."\r";
			
		}



	}

}









?>
