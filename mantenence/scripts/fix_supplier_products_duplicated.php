<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
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





$sql="select count(distinct `Supplier Product ID`) as num ,`Part SKU` from `Supplier Product Part List` L left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`)  group by `Part SKU` order by num";
//print $sql;
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {

	if ($row['num']>1) {

		$sql=sprintf("select PP.`Supplier Product Part Key`,`Supplier Product Part Valid To` from `Supplier Product Part List` L left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`)  where `Part SKU`=%d order by `Supplier Product Part Valid To` desc ",
			$row['Part SKU']
		);

		
		$res2=mysql_query($sql);
		$first=true;
		while ($row2=mysql_fetch_array($res2)) {
			if($first){
				$value='Yes';
			}else{
				$value='No';
			}
			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`=%s where `Supplier Product Part Key`=%d ",
			prepare_mysql($value),
			$row2['Supplier Product Part Key']
			);
			$first=false;
			mysql_query($sql);
			//print "$sql\n";
		}


	}
	
	$part=new Part($row['Part SKU']);
	$part->update_availability();
	$part->update_available_forecast();
	$part->update_stock_state();
	$part->update_days_until_out_of_stock();
	$part->update_used_in();

	
	$part->update_main_state();

	$part->update_supplied_by();


}







?>
