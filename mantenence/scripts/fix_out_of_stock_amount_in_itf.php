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




$sql=sprintf("select `Part SKU`,`Out of Stock`,`Date`,`Inventory Transaction Key` from `Inventory Transaction Fact` where `Out of Stock Tag`='Yes' ");


$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	
	$sku=$row['Part SKU'];
	$qty=$row['Out of Stock'];
	$date=$row['Date'];
	$itf_key=$row['Inventory Transaction Key'];
	
	$amount=get_transaction_value($sku,$qty,$date);
	
 $sql=sprintf("update `Inventory Transaction Fact` set `Out of Stock Lost Amount`=%f where   `Inventory Transaction Key`=%d",
 -1*$amount,
 $itf_key
 
 );
 mysql_query($sql);
 //print "$sql\n";
	
}

	function get_transaction_value($sku,$qty,$date=false) {

		$sql=sprintf("select sum(ifnull(`Inventory Transaction Quantity`,0)) as stock ,ifnull(sum(`Inventory Transaction Amount`),0) as value from `Inventory Transaction Fact` where  `Date`<%s and `Part SKU`=%d "
			,prepare_mysql($date)
			,$sku

		);
		$res_old_stock=mysql_query($sql);
		//print "$sql\n";
		$old_qty=0;
		$old_value=0;

		if ($row_old_stock=mysql_fetch_array($res_old_stock)) {
			$old_qty=round($row_old_stock['stock'],3);
			$old_value=$row_old_stock['value'];
		}
		$transaction_value=get_value_change($sku,-1*$qty,$old_qty,$old_value,$date);
		return $transaction_value;

	}


	function get_value_change($sku,$qty_change,$old_qty,$old_value,$date) {
		$qty=$old_qty+$qty_change;
		if ($qty_change>0) {

			list($qty_above_zero,$qty_below_zero)=$this->qty_analysis($old_qty,$qty);
			$value_change=0;
			if ($qty_below_zero) {
				$unit_cost=$old_value/$old_qty;
				$value_change+=$qty_below_zero*$unit_cost;
			}

			if ($qty_above_zero) {
				$part=new Part($sku);
				$unit_cost=$part->get_unit_cost($date);
				$value_change+=$qty_above_zero*$unit_cost;
			}


		}
		elseif ($qty_change<0) {

			list($qty_above_zero,$qty_below_zero)=qty_analysis($old_qty,$qty);

			$value_change=0;
			if ($qty_below_zero) {
				$part=new Part($sku);
				$unit_cost=$part->get_unit_cost($date);
				$value_change+=-$qty_below_zero*$unit_cost;

			}

			if ($qty_above_zero) {

				$unit_cost=$old_value/$old_qty;
				$value_change+=-$qty_above_zero*$unit_cost;

			}



		}
		else {

			$value_change=0;
		}

		return $value_change;
	}
	function qty_analysis($a,$b) {
		if ($b<$a) {
			$tmp=$a;
			$a=$b;
			$b=$tmp;
		}

		if ($a>=0 and $b>=0) {
			$above=$b-$a;
			$below=0;
		}else if ($a<=0 and $b<=0) {
				$above=0;
				$below=$b-$a;
			}else {
			$above=$b;
			$below=-$a;
		}
		return array($above,$below);

	}


?>
