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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');



$where='where `Product ID`=1991';
//$where='';
$sql=sprintf('select `Product ID`,`Product Valid From`,`Product Valid To` from `Product Dimension`  %s     ',$where);
$res2=mysql_query($sql);

while ($row2=mysql_fetch_array($res2)) {

	$product=new Product('pid',$row2['Product ID']);

	$with_out_of_stock=0;

	$sql=sprintf("select `Date` from `Order Spanshot Fact` where `Product ID`=%d order by `Date`  ",$product->pid);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_array($res)) {
		print $row['Date']." $with_out_of_stock ";
		$date=$row['Date'];

		$sql=sprintf("select IFNULL(sum(IFNULL(`No Shipped Due Out of Stock`,0)),0) as out_of_stock ,IFNULL(sum(IFNULL(`Invoice Transaction Gross Amount`,0)),0) as sales from `Order Transaction Fact` where `Product ID`=%d and `Current Dispatching State`='Dispatched' and `Invoice Date`>=%s  and `Invoice Date`<=%s   ",
			$product->pid,
			prepare_mysql($date.' 00:00:00'),
			prepare_mysql($date.' 23:59:59')

		);
		$out_of_stock=0;
		$sales=0;
			$res3=mysql_query($sql);
		if ($row3=mysql_fetch_assoc($res3)) {

			$out_of_stock=$row3['out_of_stock'];
			$sales=$row3['sales'];
			
			

		}
		
		
		
		if ($with_out_of_stock==0) {

			if ($out_of_stock!=0) {
				$avalaility=0;
			}else {
				$avalaility=1;
				$with_out_of_stock=1;
			}

		}
		else {

			if ($out_of_stock!=0) {
				$avalaility=0;
			}else {

				if ($sales>0) {
					$with_out_of_stock=0;
					$avalaility=1;
				}else {
					$avalaility=0;
				}

			}



		}

	print "OoS: $out_of_stock S: $sales ; $avalaility\n";


	$sql=sprintf("update `Order Spanshot Fact` set `Availability`=%f where `Product ID`=%d  and  `Date`=%s  ",
	$avalaility,
	$product->pid,
	prepare_mysql($date)
	);
	mysql_query($sql);
	//print "$sql\n";

	}


}





?>
