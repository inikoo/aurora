<?php
include_once '../../conf/dns.php';
include_once '../../class.Account.php';
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


$inikoo_account=new Account();
$corporate_currency=$inikoo_account->data['Account Currency'];
$account_code=$inikoo_account->data['Account Code'];



$from=date("Y-m-d",strtotime('now '));
$to=date("Y-m-d",strtotime('now '));


$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s order by `Date` desc",
	prepare_mysql($from),prepare_mysql($to));
$res=mysql_query($sql);

while ($row=mysql_fetch_assoc($res)) {

	$where=sprintf(" `Product Main Type` in ('Historic','Discontinued')  and  `Product Valid From`<=%s and `Product Valid To`>=%s ",
		prepare_mysql($row['Date'].' 00:00:00'),
		prepare_mysql($row['Date'].' 23:59:59')

	);
	$sql=sprintf('select `Product ID`  from `Product Dimension` where %s     ',$where);
	$res2=mysql_query($sql);
	$count=0;
	while ($row2=mysql_fetch_array($res2)) {
		$product=new Product("pid",$row2['Product ID']);
		$product->create_time_series($row['Date']);
		// $product->update_sales_averages();
		//print $row['Date']." disc ".$product->code."\n";

	}


	$where=sprintf("   `Product Main Type` not in ('Historic','Discontinued')  and  `Product Valid From`<=%s  ",
		prepare_mysql($row['Date'].' 00:00:00')
	);
	$sql=sprintf('select `Product ID`  from `Product Dimension` where %s     ',$where);
	$res2=mysql_query($sql);
	//print "$sql\n";
	$count=0;
	while ($row2=mysql_fetch_assoc($res2)) {
		$product=new Product("pid",$row2['Product ID']);
		$product->create_time_series($row['Date']);
		
	}

	

}



$sql="select `Store Key`  from `Store Dimension` ";
$result2=mysql_query($sql);
while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {

	$fields=' `Date`,sum(`Sales`) as Sales';
	$where=sprintf("where `Store Key`=%d",$row2['Store Key']);
	$group='group by `Date`';

	$cache = new Memcached();
	$cache->addServer($memcache_ip, 11211);
	$sql=sprintf("select %s from `Order Spanshot Fact` %s %s",
		$fields,
		$where,
		$group
	);
	
	$res=mysql_query($sql);
		$result=array();
		while ($row=mysql_fetch_assoc($res)) {
			$result[]=$row;
		}
		$cache->set($account_code.'SQL'.md5($sql),$result,86400 );

}


?>
