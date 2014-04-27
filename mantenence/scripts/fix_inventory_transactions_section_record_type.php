<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.PartLocation.php';

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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql=sprintf("select `Inventory Transaction Key`,`Inventory Transaction Type`  from `Inventory Transaction Fact` ITF  ");
// print $sql;

$result2=mysql_query($sql);

while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
	//print $row2['Inventory Transaction Key']."\n";

	if (in_array($row2['Inventory Transaction Type'],array('Move In','Move Out','Associate','Disassociate'))) {
		$record_type='Helper';
	}else {
		$record_type='Movement';
	}



	switch ($row2['Inventory Transaction Type']) {
	case 'Order In Process':
		$section='OIP';
		break;
	case('In'):
		$section='In';
		break;
	case('Move'):
		$section='Move';
		break;
	case('Sale'):
	case('Broken'):
	case('Lost'):
	case('Other Out'):
		$section='Out';
		break;
	
	
	case('Audit'):
	case('Adjust'):
		$section='Audit';
		break;
	case('Not Found'):
	case('No Dispatched'):
	
		$section='NoDispatched';
		break;
	default:
	case('Other'):
	$section='Other';
		break;
	}




	$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Record Type`=%s , `Inventory Transaction Section`=%s where `Inventory Transaction Key`=%d",
		prepare_mysql($record_type),
		prepare_mysql($section),
		$row2['Inventory Transaction Key']);
//	print "$sql\n";
	mysql_query($sql);







}

?>
