
<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.PartLocation.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
setlocale(LC_MONETARY, 'en_GB.UTF-8');

global $myconf;

$sql="select * from `Inventory Transaction Fact` where `Inventory Transaction Type`='Move Out'   ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$part_location=new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
	$sql=sprintf("select * from `Inventory Transaction Fact` where `Inventory Transaction Type`='Move In' and  `Part SKU`=%d  and `Date`=%s   ",
		$row['Part SKU'],prepare_mysql($row['Date'])
	);
	print "$sql\n";
	$result2=mysql_query($sql);
	if ($row1=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {




$sql=sprintf("select count(*) as num  from `Inventory Transaction Fact` where `Inventory Transaction Type`='Move' and `Date`=%s   ",
		prepare_mysql($row['Date'])
	);
	
	$result3=mysql_query($sql);
	if ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
if($row3['num']>0)
	continue;
}
		
		$destination=new PartLocation($row1['Part SKU'].'_'.$row1['Location Key']);

		$details=_('Inter-warehouse transfer').' <b>['.number($row1['Inventory Transaction Quantity']).']</b>,  <a href="location.php?id='.$part_location->location->id.'">'.$part_location->location->data['Location Code'].'</a> &rarr; <a href="location.php?id='.$destination->location->id.'">'.$destination->location->data['Location Code'].'</a>';

		$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Relations`) values (%d,%d,%s,%f,%.2f,%s,%s,%s,%s)"
			,$part_location->part_sku
			,$destination->location->id
			,prepare_mysql('Move')
			,0
			,0
			,$row['User Key']
			,prepare_mysql($details,false)
			,prepare_mysql($row['Date'])
			,prepare_mysql($row['Inventory Transaction Key'].','.$row1['Inventory Transaction Key'])


		);
		//print "$sql\n";
		mysql_query($sql);
		$move_transaction_key=mysql_insert_id();

		$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%d  where `Inventory Transaction Key`=%d ",
			$move_transaction_key,
			$row['Inventory Transaction Key']
		);
		mysql_query($sql);

		$sql=sprintf("update `Inventory Transaction Fact` set `Relations`=%d  where `Inventory Transaction Key`=%d ",
			$move_transaction_key,
			$row1['Inventory Transaction Key']
		);
		mysql_query($sql);
	}


}
?>
