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


$sql="select * from `Part Dimension`  order by `Part SKU` desc ";

$resultxx=mysql_query($sql);
while ($rowxx=mysql_fetch_array($resultxx, MYSQL_ASSOC)   ) {

	$part_sku=$rowxx['Part SKU'];
	//$part_sku=35539;
	print "$part_sku\r";
	$value=0;
	$stock=0;

	$sql="select `Inventory Transaction Type`,`Inventory Transaction Amount`,`Inventory Transaction Key`,`Inventory Transaction Quantity`,`Part SKU`,`Date` from `Inventory Transaction Fact` where `Part SKU`=".$part_sku." order by `Date`  ";


	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

		$date=$row['Date'];
		$qty=$row['Inventory Transaction Quantity'];
		$type=$row['Inventory Transaction Type'];


		$transaction_value=0;

		if ($qty==0) {
			$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Amount`=0 where `Inventory Transaction Key`=%d ",$row['Inventory Transaction Key']
			);
			mysql_query($sql);

		}
		else if (($type=='Adjust' or  $type=='In' or  $type=='Move Out'  )and $qty>0) {




				$new_stock=$qty+$stock;

				list($qty_above_zero,$qty_below_zero)=qty_analysis($stock,$new_stock);
				$transaction_value=0;
				if ($qty_below_zero) {
					$unit_cost=$value/$stock;
					$transaction_value+=$qty_below_zero*$unit_cost;
					// print "Below: $qty_below_zero, $unit_cost  ".$qty_above_zero*$unit_cost."  \n";

				}

				if ($qty_above_zero) {

					$part=new Part($row['Part SKU']);
					$unit_cost=$part->get_unit_cost($date);
					$transaction_value+=$qty_above_zero*$unit_cost;

					//        print "Above:$qty_above_zero, $unit_cost ".$qty_above_zero*$unit_cost."\n";


				}

				// print "S:".$old_value." V:".$old_stock." $new_stock --  $qty $transaction_value \n";
				//exit;


				$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Amount`=%.3f where `Inventory Transaction Key`=%d ",
					$transaction_value,
					$row['Inventory Transaction Key']

				);

				mysql_query($sql);


				if ($type=='Move Out') {
					fix_move_values($row['Inventory Transaction Key'],$transaction_value);

				}


			}

		else if (($type=='Adjust' or $type=='Sale' or $type=='In' or $type=='Broken' or $type=='Lost' or $type=='Other Out') and $qty<0  ) {






				$new_stock=$qty+$stock;
				list($qty_above_zero,$qty_below_zero)=qty_analysis($stock,$new_stock);



				$transaction_value=0;
				if ($qty_below_zero) {
					$part=new Part($row['Part SKU']);
					$unit_cost=$part->get_unit_cost($date);
					$transaction_value+=-$qty_below_zero*$unit_cost;

				}

				if ($qty_above_zero) {

					$unit_cost=$value/$stock;
					$transaction_value+=-$qty_above_zero*$unit_cost;

				}

				//print "A: $stock,$new_stock  $qty_below_zero,$qty_above_zero  TV: $transaction_value  \n ";

				// print "S:".$old_value." V:".$old_stock." $new_stock --  $qty $transaction_value \n";
				//exit;

				$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Amount`=%.3f where `Inventory Transaction Key`=%d ",
					$transaction_value,
					$row['Inventory Transaction Key']

				);
				//print $sql;
				mysql_query($sql);
				if ($type=='Move Out') {
					fix_move_values($row['Inventory Transaction Key'],$transaction_value);

				}


			}

		$value=round($value+$transaction_value,6);
		$stock=round($stock+$qty,6);
		if ($stock==0) {$__unit_cost="ND";}else {$__unit_cost=$value/$stock;}
		//print "$date $type \t\t$qty\t\t $transaction_value \t\t$stock \t\t$value\t$__unit_cost\n";


	}

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




function fix_move_values($itf_key,$transaction_value) {

	$sql=sprintf("select * from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d ",$itf_key);

	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


		$part_location=new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
		$sql=sprintf("select * from `Inventory Transaction Fact` where `Inventory Transaction Type`='Move In' and  `Part SKU`=%d  and `Date`=%s   ",
			$row['Part SKU'],prepare_mysql($row['Date'])
		);
		//print "$sql\n";
		$result2=mysql_query($sql);
		if ($row1=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {

			$sql=sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Amount`=%.3f where `Inventory Transaction Key`=%d ",
				-$transaction_value,
				$row1['Inventory Transaction Key']

			);

			mysql_query($sql);






		}


	}


}




?>
