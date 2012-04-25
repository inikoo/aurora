<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
include_once '../../class.PartLocation.php';
include_once '../../class.User.php';
include_once '../../class.InventoryAudit.php';

error_reporting(E_ALL);
error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );


if (!$con) {print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');



$from=date("Y-m-d");

$to="2007-01-01";
/*
$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`<=%s and `Date`>=%s order by `Date` desc",
	prepare_mysql($from),prepare_mysql($to));
$res=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_array($res)) {
	
	$where=' true';
	$sql=sprintf('select `Part SKU`,`Location Key` from `Part Location Dimension` where %s     ',$where);

	//print $sql;
	$res2=mysql_query($sql);
	$count=0;
	while ($row2=mysql_fetch_array($res2)) {

print $row['Date']."\t".$row2['Part SKU'].'_'.$row2['Location Key']."\t\r";
		$part_location=new PartLocation($row2['Part SKU'].'_'.$row2['Location Key']);
		$part_location->update_stock_history_date($row['Date']);

	}

}


exit;
*/
$from=date("Y-m-d",strtotime('now -1 day'));
$from=date("Y-m-d");
$to=date("Y-m-d");

$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s order by `Date` desc",
	prepare_mysql($from),prepare_mysql($to));
$res=mysql_query($sql);

while ($row=mysql_fetch_array($res)) {
	print $row['Date']."\r";
	$where=' `Part SKU`=39077';
	$where='  true';
	$sql=sprintf('select `Part SKU` from `Part Dimension` where %s     ',$where);

	//print $sql;
	$res2=mysql_query($sql);
	$count=0;
	while ($row2=mysql_fetch_array($res2)) {
		//print "\t\t\t\tChecking:".$row2['Part SKU']."\r";
		$sql=sprintf("select `Location Key`  from `Inventory Transaction Fact` where  `Inventory Transaction Type`='Associate' and  `Part SKU`=%d and `Date`<=%s group by `Location Key`",
		$row2['Part SKU'],
		prepare_mysql($row['Date'])
		);
		//print "$sql\n";
		$result=mysql_query($sql);
		$_locations=array();
		while ($row3=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

			if (in_array($row3['Location Key'],$_locations)) {
				continue;
			}else {
				$_locations[]=$row3['Location Key'];
			}

			$part_location=new PartLocation($row2['Part SKU'].'_'.$row3['Location Key']);
			
			//print $row2['Part SKU'].'_'.$row3['Location Key']."\n";
			
			$part_location->update_stock_history_date($row['Date']);
		}



	}

}

exit;

//$where='and  `Part XHTML Currently Used In` like "%awred%"';
//$where='and `Part SKU`=4303';
$where='';
$sql=sprintf('select count(*) as num  from `Part Dimension` where `Part Status`="In Use" %s   order by `Part Valid From`  ',$where);
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$total=$row['num'];
}


//print "Wrap part transactions\n";
$sql=sprintf('select `Part SKU`,`Part XHTML Currently Used In`  from `Part Dimension` where `Part Status`="In Use"   %s  order by `Part Total Acc Sold Amount` desc ,`Part Valid From`   ',$where);
$sql=sprintf('select `Part SKU`,`Part XHTML Currently Used In`  from `Part Dimension` where `Part Status`="In Use"   %s     ',$where);

//print $sql;
$res=mysql_query($sql);
$count=0;
while ($row=mysql_fetch_array($res)) {
	$count++;

	$part=new Part($row['Part SKU']);

	print percentage($count,$total,5)."  ".$part->data['Part SKU']."\r";
	$part->update_stock_history();


}









?>
