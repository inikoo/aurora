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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$sql=sprintf("select `Part SKU` from `Part Dimension` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$part=new Part($row['Part SKU']);
print $part->data['Part Reference']."\n";
	$sql=sprintf("select * from  dw.`Part Dimension` where `Part Reference`=%s",prepare_mysql($part->data['Part Reference']));
	$res2=mysql_query($sql);
	if ($row2=mysql_fetch_assoc($res2)) {

		if ($row2['Part Tariff Code']!='') {
			$part->update(array('Part Tariff Code'=>$row2['Part Tariff Code']));
		}
		if ($row2['Part Duty Rate']!='') {
			$part->update(array('Part Duty Rate'=>$row2['Part Duty Rate']));
		}

		if ($row2['Part UN Number']!='') {
			$part->update(array('Part UN Number'=>$row2['Part UN Number']));
		}

		if ($row2['Part UN Class']!='') {
			$part->update(array('Part UN Class'=>$row2['Part UN Class']));
		}
		if ($row2['Part Health And Safety']!='') {
			$part->update(array('Part Health And Safety'=>$row2['Part Health And Safety']));
		}
		if ($row2['Part Packing Group']!='') {
			$part->update(array('Part Packing Group'=>$row2['Part Packing Group']));

		}
		if ($row2['Part Proper Shipping Name']!='') {
			$part->update(array('Part Proper Shipping Name'=>$row2['Part Proper Shipping Name']));
		}
		if ($row2['Part Hazard Indentification Number']!='') {
			$part->update(array('Part Hazard Indentification Number'=>$row2['Part Hazard Indentification Number']));
		}

		if ($row2['Part Unit Dimensions Width Display']  or $row2['Part Unit Dimensions Depth Display'] or $row2['Part Unit Dimensions Length Display'] or $row2['Part Unit Dimensions Diameter Display'] ) {
			$part->update(
				array(
					'Part Unit Dimensions Type'=>$row2['Part Unit Dimensions Type'],
					'Part Unit Dimensions Display Units'=>$row2['Part Unit Dimensions Display Units'],
					'Part Unit Dimensions Width Display'=>$row2['Part Unit Dimensions Width Display'],
					'Part Unit Dimensions Depth Display'=>$row2['Part Unit Dimensions Depth Display'],
					'Part Unit Dimensions Length Display'=>$row2['Part Unit Dimensions Length Display'],
					'Part Unit Dimensions Diameter Display'=>$row2['Part Unit Dimensions Diameter Display']

				)

			);
		}


		if ($row2['Part Package Dimensions Width Display']  or $row2['Part Package Dimensions Depth Display'] or $row2['Part Package Dimensions Length Display'] or $row2['Part Package Dimensions Diameter Display'] ) {
			$part->update(
				array(
					'Part Package Dimensions Type'=>$row2['Part Package Dimensions Type'],
					'Part Package Dimensions Display Units'=>$row2['Part Package Dimensions Display Units'],
					'Part Package Dimensions Width Display'=>$row2['Part Package Dimensions Width Display'],
					'Part Package Dimensions Depth Display'=>$row2['Part Package Dimensions Depth Display'],
					'Part Package Dimensions Length Display'=>$row2['Part Package Dimensions Length Display'],
					'Part Package Dimensions Diameter Display'=>$row2['Part Package Dimensions Diameter Display']

				)

			);
		}



		if ($row2['Part Unit Weight Display']) {
			$part->update(
				array(
					'Part Unit Weight Display'=>$row2['Part Unit Weight Display'],
					'Part Unit Weight Display Units'=>$row2['Part Unit Weight Display Units'],
				)
			);
		}

		if ($row2['Part Package Weight Display']) {
			$part->update(
				array(
					'Part Package Weight Display'=>$row2['Part Package Weight Display'],
					'Part Package Weight Display Units'=>$row2['Part Package Weight Display Units'],
				)
			);
		}

		if ($row2['Part Origin Country Code']!='') {
			$part->update(array('Part Origin Country Code'=>$row2['Part Origin Country Code']));
		}
		if ($row2['Part Unit Materials']!='') {
			$part->update(array('Part Unit Materials'=>$row2['Part Unit Materials']));
		}



	}


}





?>
