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



$sql="select count(*) as total from `Product Dimension`";
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$total=$row['total'];
}
$contador=0;



$where='where `Product ID`=1991';
$where='';
$sql=sprintf('select `Product ID`,`Product Valid From`,`Product Valid To` from `Product Dimension`  %s     ',$where);
$res2=mysql_query($sql);
$lap_time0=date('U');
while ($row2=mysql_fetch_array($res2)) {

	$product=new Product('pid',$row2['Product ID']);
	$from=$row2['Product Valid From'];
	$to=$row2['Product Valid To'];

	if ($from=='') {
		print $product->code." invalid from \n";
		continue;
	}
	if ($to=='') {
		$to=gmdate("Y-m-d");
	}




	$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s order by `Date` desc",
		prepare_mysql($from),prepare_mysql($to));
	$res=mysql_query($sql);
	//print $sql;
	while ($row=mysql_fetch_array($res)) {
		$product->create_time_series($row['Date']);

	}
	
		$contador++;
	$lap_time1=date('U');
print 'P ('.$contador.'/'.$total.')  Time '.percentage($contador,$total,3)."  time  ".sprintf("%.2f",($lap_time1-$lap_time0))." lap  ".sprintf("%.2f",($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h \r";

	

}





?>
