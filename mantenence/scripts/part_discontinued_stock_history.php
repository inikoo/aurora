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
date_default_timezone_set('UTC');

$where='and   `Part XHTML Currently Used In` like "%lebt%"';
$where='and `Part SKU`=37719';
$where='';
$sql=sprintf('select count(*) as num  from `Part Dimension`  where `Part Status`="Not In Use" %s  order by `Part SKU` desc ',$where);
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$total=$row['num'];
}


//print "Wrap part transactions\n";
$sql=sprintf('select `Part SKU`,`Part XHTML Currently Used In`  from `Part Dimension`  where `Part Status`="Not In Use" %s order by `Part SKU` desc',$where);
$res=mysql_query($sql);
$count=0;
while ($row=mysql_fetch_array($res)) {
	$count++;


	$part=new Part($row['Part SKU']);




	$sql=sprintf("delete from `Part Location Dimension` where `part SKU`=%d",$part->sku);
	mysql_query($sql);


	//  $part->wrap_transactions();




	$sql=sprintf("select `Location Key` from `Inventory Transaction Fact` where  `Part SKU`=%d  group by `Location Key`  ",$part->sku);
	$res2=mysql_query($sql);
	while ($row2=mysql_fetch_array($res2)) {
		$location_key=$row2['Location Key'];
		//  print "Location $location_key\n";



		$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date`   ",$part->sku,$location_key);
		//print "$sql\n";
		$res3=mysql_query($sql);
		while ($row3=mysql_fetch_array($res3)) {
			if ($row3['Inventory Transaction Type']=='Associate') {
				$sql=sprintf("delete from  `Inventory Transaction Fact` where `Part SKU`=%d  and `Inventory Transaction Type` in ('Associate') and `Date`=%s and `Location Key`=%d  "
					,$part->sku
					,prepare_mysql($row3['Date'])
					,$location_key
				);
				//  print "$sql\n";
				mysql_query($sql);
			}
		}

		$sql=sprintf("select `Date`,`Inventory Transaction Type` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc ,`Event Order` desc ",$part->sku,$location_key);
		$last_itf_date='none';
		$res3=mysql_query($sql);
		//      print "$sql\n";
		if ($row3=mysql_fetch_array($res3)) {
			if ($row3['Inventory Transaction Type']=='Disassociate') {
				$sql=sprintf("delete from  `Inventory Transaction Fact` where `Part SKU`=%d  and `Inventory Transaction Type` in ('Disassociate') and `Date`=%s and `Location Key`=%d  "
					,$part->sku
					,prepare_mysql($row3['Date'])
					,$location_key
				);
				//print "$sql\n";
				mysql_query($sql);
			}
		}





		$sql=sprintf('select `Inventory Audit Date` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d  order by `Inventory Audit Date`' ,$part->sku,$location_key);
		$first_audit_date='none';
		$res3=mysql_query($sql);
		if ($row3=mysql_fetch_array($res3)) {
			$first_audit_date=($row3['Inventory Audit Date']);
		}
		//    print "\n$sql\n";
		$sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date`  ",$part->sku,$location_key);
		$first_itf_date='none';
		$res3=mysql_query($sql);
		if ($row3=mysql_fetch_array($res3)) {
			$first_itf_date=($row3['Date']);
		}
		//print "$sql\n";
		  // print "R: $first_audit_date : $first_itf_date \n ";




		if ($first_audit_date=='none' and $first_itf_date=='none') {


			$sql=sprintf('select count(*) as num from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  ' ,$part->sku,$location_key);
			$res10=mysql_query($sql);

			$transactions=0;
			if ($row10=mysql_fetch_array($res10)) {
				$transactions=$row10['num'];
			}

			if ($transactions==0) {
				print "no transactions\n";
				continue ;
			} else {
			
				$sql=sprintf('select *   from `Inventory Transaction Fact` where  `Part SKU`=%d  ' ,$part->sku);
			$res100=mysql_query($sql);
				while($_row10=mysql_fetch_assoc($res100)){
					//print_r($_row10);
				}
				print "\nError1 : Part ".$part->sku." ".$part->data['Part XHTML Currently Used In']."  \n";
				return;
			}
		}
		elseif ($first_audit_date=='none') {
			$first_date=$first_itf_date;
		}
		elseif ($first_itf_date=='none') {
			$first_date=$first_audit_date;
		}
		else {
		
			if (strtotime($first_itf_date)<strtotime($first_audit_date) )
				$first_date=$first_itf_date;
			else
				$first_date=$first_audit_date;

		}


		$part_location_data=array(
			'Part SKU'=>$part->sku,
			'Location Key'=>$location_key,
			'Date'=>$first_date);

		//print_r($part_location_data);
		//exit;
		$part_location=new PartLocation('find',$part_location_data,'create');

		if ($part_location->found) {

			$sql=sprintf("delete from  `Inventory Transaction Fact` where `Inventory Transaction Type` in ('Associate') where `Part SKU`=%d and `Location Key`=%d  limit 1 "
				,$this->sku
				,$location_key
			);

			mysql_query($sql);
			$location=new Location($location_key);
			$details=_('Part')." SKU".sprintf("%05d",$part->sku)." "._('associated with location').": ".$location->data['Location Code'];
			$sql=sprintf("insert into `Inventory Transaction Fact` (`Part SKU`,`Location Key`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`User Key`,`Note`,`Date`,`Event Order`) values (%d,%d,%s,%f,%.2f,%s,%s,%s,%s)"
				,$part->sku
				,$location_key
				,"'Associate'"
				,0
				,0
				,0
				,prepare_mysql($details)
				,prepare_mysql($first_date)
				,-2
			);
			mysql_query($sql);
		}







		$sql=sprintf('select `Inventory Audit Date` from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d and `Inventory Audit Location Key`=%d  order by `Inventory Audit Date` desc' ,$part->sku,$location_key);
		$last_audit_date='none';
		$res3=mysql_query($sql);
		if ($row3=mysql_fetch_array($res3)) {
			$last_audit_date=($row3['Inventory Audit Date']);
		}

		$sql=sprintf("select `Date` from `Inventory Transaction Fact` where  `Part SKU`=%d and `Location Key`=%d  order by `Date` desc  ",$part->sku,$location_key);
		$last_itf_date='none';
		$res3=mysql_query($sql);
		if ($row3=mysql_fetch_array($res3)) {
			$last_itf_date=($row3['Date']);
		}
		// print $sql;
		if ($last_audit_date=='none' and $last_itf_date=='none') {
			print "\nError2: Part ".$part->sku." ".$part->data['Part XHTML Currently Used In']."  \n";
			return;
		}
		elseif (!$last_audit_date) {
			$last_date=$last_itf_date;
		}
		elseif (!$last_itf_date) {
			$last_date=$last_audit_date;
		}
		else {
			if (strtotime($last_itf_date)>strtotime($last_audit_date) )
				$last_date=$last_itf_date;
			else
				$last_date=$last_audit_date;

		}

		$part_location->set_audits();
		$data=array('Date'=>$last_date,'Note'=>_('Discontinued'),'Event Order'=>1);


		$intervals=$part_location->get_history_intervals();
		$intervals[count($intervals)-1]['To']=date("Y-m-d",strtotime($last_date));
		foreach ($intervals as $interval) {
			//print_r($interval);
			$from=$interval['From'];
			$to=($interval['To']?$interval['To']:date('Y-m-d',strtotime('now -1 day')));
			//print "$from $to\n";
			$part_location->update_stock_history_interval($from,$to);
		}


		//print_r($intervals);
		$part_location->disassociate($data);
		$part->update_valid_to($last_date);


		$part->update_stock();


	print percentage($count,$total,5)."  ".$part->data['Part SKU']."  $from $to\r";


	}

















}







?>
