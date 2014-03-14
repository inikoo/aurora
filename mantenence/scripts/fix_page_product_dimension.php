<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Deal.php';
include_once '../../class.Charge.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Warehouse.php';
include_once '../../class.Node.php';
include_once '../../class.Shipping.php';
include_once '../../class.SupplierProduct.php';
include_once 'local_map.php';

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
$codigos=array();


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql=sprintf("select * from `Page Product Dimension`");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
	$page=new Page($row['Page Key']);
	if($page->id){
	$sql=sprintf("update `Page Product Dimension` set `State`=%s  where `Page Key`=%d ",
	prepare_mysql($page->data['Page State']),
	$row['Page Key']
	
	);
	//print "$sql\n";
	mysql_query($sql);
}else{
	$sql=sprintf("delete `Page Product Dimension`   where `Page Key`=%d ",
	$row['Page Key']
	
	);
	print "$sql\n";
}


}

?>
