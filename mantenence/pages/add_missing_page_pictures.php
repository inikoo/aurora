<?php
include_once '../../conf/dns.php';
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
include_once '../../class.Image.php';
include_once '../../class.Page.php';

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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql=sprintf("select `Page Key` from  `Page Store Dimension` where `Page Store Image Key`=0 or `Page Store Image Key` is NULL  ");

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {

	$page=new Page($row['Page Key']);

	$page->update_image_key();
}




?>
