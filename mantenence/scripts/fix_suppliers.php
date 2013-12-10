<?php
/*

 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 December 2013 22:43:55 CET, Malaga Spain
 Copyright (c) 2013, Inikoo

 Version 2.0
*/



include_once '../../app_files/db/dns.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
date_default_timezone_set('UTC');

error_reporting(E_ALL);
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
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';



$sql="select count(*) as number,`Supplier Code`,`Supplier Key` from `Supplier Dimension` group by `Supplier Code` ";
$resx=mysql_query($sql);
while ($rowx=mysql_fetch_assoc($resx)) {
	if ($rowx['number']>1) {
		print $rowx['Supplier Code']."\n";

	$sql=sprintf("select count(*) as number,`Supplier Code`,`Supplier Key` from `Supplier Dimension` where `Supplier Code`=%s ",
	prepare_mysql($rowx['Supplier Code'])
	);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($resx)) {
	if ($row['number']>1) {
		print $row['Supplier Code']."\n";

	

	}
}

	}
}





?>
