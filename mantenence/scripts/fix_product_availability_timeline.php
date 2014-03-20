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


$sql=sprintf("select * from `Product Availability Timeline`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$product=new Product('pid',$row['Product ID']);
	$sql=sprintf("update `Product Availability Timeline` set `Store Key`=%d,`Deparment Key`=%d,`Family Key`=%d  where `Product Availability Key`=%d ",
		$product->data['Product Store Key'],
		$product->data['Product Main Department Key'],
		$product->data['Product Family Key'],
		$row['Product Availability Key']

	);
	mysql_query($sql);

}

$sql=sprintf("select `Product ID` from `Product Availability Timeline` group by `Product ID` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
$contador=0;
$sql=sprintf("select `Availability`,`Product Availability Key`,UNIX_TIMESTAMP(`Date`) as date from `Product Availability Timeline` where `Product ID`=%d order by `Date` desc ,`Product Availability Key` desc ",
	$row['Product ID']
	);
	//print "$sql\n";
	$res2=mysql_query($sql);
	while ($row2=mysql_fetch_assoc($res2)) {
	$current_state=$row2['Availability'];
	if($contador){
		if($current_state==$last_state){
			$sql=sprintf("delete from `Product Availability Timeline` where `Product Availability Key`=%d",
			$row2['Product Availability Key']
			);
			//print "$sql\n";
			mysql_query($sql);
		}
	}
	$last_state=$row2['Availability'];
	$contador++;
	}
	



	$contador=0;

	$sql=sprintf("select `Product Availability Key`,UNIX_TIMESTAMP(`Date`) as date from `Product Availability Timeline` where `Product ID`=%d order by `Date` desc ,`Product Availability Key` desc ",
	$row['Product ID']
	);
	//print "$sql\n";
	$res2=mysql_query($sql);
	while ($row2=mysql_fetch_assoc($res2)) {




		if ($contador>0) {
			$current_date=$row2['date'];
			$duration=$last_date-$current_date;
			$sql=sprintf("update `Product Availability Timeline` set `Duration`=%d  where `Product Availability Key`=%d ",
				$duration,
				$row2['Product Availability Key']

			);
			//print "$sql\n";
			mysql_query($sql);
		}
		$last_date=$row2['date'];
		$last_key=$row2['Product Availability Key'];
		$contador++;
	}


}


//=====================

$sql=sprintf("select `Part SKU` from `Part Availability for Products Timeline` group by `Part SKU` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
$contador=0;
$sql=sprintf("select `Availability for Products`,`Part Availability for Products Key`,UNIX_TIMESTAMP(`Date`) as date from `Part Availability for Products Timeline` where `Part SKU`=%d order by `Date` desc ,`Part Availability for Products Key` desc ",
	$row['Part SKU']
	);
	//print "$sql\n";
	$res2=mysql_query($sql);
	while ($row2=mysql_fetch_assoc($res2)) {
	$current_state=$row2['Availability for Products'];
	if($contador){
		if($current_state==$last_state){
			$sql=sprintf("delete from `Part Availability for Products Timeline` where `Part Availability for Products Key`=%d",
			$row2['Part Availability for Products Key']
			);
			//print "$sql\n";
			mysql_query($sql);
		}
	}
	$last_state=$row2['Availability for Products'];
	$contador++;
	}
	



	$contador=0;

	$sql=sprintf("select `Part Availability for Products Key`,UNIX_TIMESTAMP(`Date`) as date from `Part Availability for Products Timeline` where `Part SKU`=%d order by `Date` desc ,`Part Availability for Products Key` desc ",
	$row['Part SKU']
	);
	//print "$sql\n";
	$res2=mysql_query($sql);
	while ($row2=mysql_fetch_assoc($res2)) {




		if ($contador>0) {
			$current_date=$row2['date'];
			$duration=$last_date-$current_date;
			$sql=sprintf("update `Part Availability for Products Timeline` set `Duration`=%d  where `Part Availability for Products Key`=%d ",
				$duration,
				$row2['Part Availability for Products Key']

			);
			//print "$sql\n";
			mysql_query($sql);
		}
		$last_date=$row2['date'];
		$last_key=$row2['Part Availability for Products Key'];
		$contador++;
	}


}




?>
