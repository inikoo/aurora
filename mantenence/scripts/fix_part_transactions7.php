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
include_once '../../class.DeliveryNote.php';

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


$sql=sprintf("select `Dispatch Country Code`,`Inventory Transaction Key`,`Delivery Note Key` from `Inventory Transaction Fact`  ");
 //print $sql;

$result2=mysql_query($sql);

while ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {

	

		$dn=new DeliveryNote($row2['Delivery Note Key']);
	//	if ($row2['Dispatch Country Code']!='UNK') {
		//print_r($dn);
if ($dn->id) {
		if ($dn->data['Delivery Note Country Code']=='') {
			$country_code='UNK';
		}else {
			$country_code=$dn->data['Delivery Note Country Code'];
		}

		
			$sql=sprintf("update `Inventory Transaction Fact` set `Dispatch Country Code`=%s where `Inventory Transaction Key`=%d",
				prepare_mysql($country_code),
				$row2['Inventory Transaction Key']

			);
			//print "$sql\n";
			//exit;
			mysql_query($sql);

		}
		unset($dn);

//	}


}

?>
