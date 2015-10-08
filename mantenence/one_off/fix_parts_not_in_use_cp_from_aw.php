<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
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

$con=@mysql_connect($dns_host, $dns_user, $dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql="select  `Part Reference` from dw.`Part Dimension` where `Part Status`='Not In Use' ";
$contador0=0;
$contador1=0;
$contador2=0;
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$part=new Part('reference', $row['Part Reference']);
	if (!$part->sku) {
		$contador0++;
		//print $contador0.' '.$row['Part Reference'];
		continue;
	}
	if ($part->data['Part Status']=='Not In Use')continue;

	if ($part->get('Part Current Stock')<=1) {
		$contador1++;
		//print 'TO NiU '.$contador1.' '.$part->sku.'  '.$part->get('Part Reference')." ".$part->get('Part Current Stock')."  ".$part->get("Part Total Acc Sold")." \n";
		print $part->get('Part Reference')."\n";

       //  $part->update_status('Not In Use');
	}else {
		$contador2++;
		//print $contador2.' '.$part->sku.'  '.$part->get('Part Reference')." ".$part->get('Part Current Stock')." \n";
		//print $part->get('Part Reference')." ".$part->get('Part Current Stock')." \n";

	}



}


?>
