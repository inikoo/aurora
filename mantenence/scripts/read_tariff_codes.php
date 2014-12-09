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

$_department_code='';
$software='Get_Products.php';
$version='V 1.1';

$Data_Audit_ETL_Software="$software $version";

$set_part_as_available=false;

$csv_file='/tmp/tariff_codes.csv';


$handle_csv = fopen($csv_file, "r");
$column=0;

$count=0;

$__cols=array();
$inicio=false;
while (($_cols = fgetcsv($handle_csv))!== false) {

	if (_trim($_cols[1])!='' and _trim($_cols[1])!='') {
		$tariff_code=_trim($_cols[1]);
		$duty_rate=_trim($_cols[2]);
		$code=_trim($_cols[0]);
		//print "$code $tariff_code $duty_rate\n";
		$part=new Part('reference',$code);
		$part->update(array('Part Tariff Code'=>$tariff_code,'Part Duty Rate'=>$duty_rate));
	}

}



?>
