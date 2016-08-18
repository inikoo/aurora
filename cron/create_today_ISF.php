<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 August 2016 at 19:10:17 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.Part.php';
require_once 'class.Location.php';
require_once 'class.PartLocation.php';
require_once 'class.Warehouse.php';
require_once 'class.Product.php';

require_once 'utils/date_functions.php';


//$from='2006-04-07';
//$to=date("Y-m-d",strtotime('now -1 day'));


$from=date("Y-m-d", strtotime('now'));
$to=date("Y-m-d", strtotime('now'));


//$from=date("Y-m-d");
//$to='2013-09-05';



$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s order by `Date` desc",
	prepare_mysql($from), prepare_mysql($to));


if ($result=$db->query($sql)) {
	foreach ($result as $row) {

		$where=' `Part SKU`=46433';
		$where='  true';

		$count=0;
		$sql=sprintf('select `Part SKU` from `Part Dimension` where %s  order by `Part SKU` ', $where);

       // print "$sql\n";

		if ($result2=$db->query($sql)) {
			foreach ($result2 as $row2) {

                //print "----".$row2['Part SKU']."\n";

				$sql=sprintf("select `Location Key`  from `Inventory Transaction Fact` where  `Inventory Transaction Type` like 'Associate' and  `Part SKU`=%d and `Date`<=%s group by `Location Key`",
					$row2['Part SKU'],
					prepare_mysql($row['Date'].' 23:59:59')
				);


				if ($result3=$db->query($sql)) {
					foreach ($result3 as $row3) {
						print $row['Date'].' '.$row2['Part SKU'].'_'.$row3['Location Key']."\r";

						$part_location=new PartLocation($row2['Part SKU'].'_'.$row3['Location Key']);
						$part_location->update_stock_history_date($row['Date']);



					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}




			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}



		$sql=sprintf('select `Warehouse Key` from `Warehouse Dimension`');
		if ($result2=$db->query($sql)) {
			foreach ($result2 as $row2) {
				$warehouse=new Warehouse($row2['Warehouse Key']);
				//$warehouse->update_inventory_snapshot($row['Date']);
			}
		}else {
			print_r($error_info=$db->errorInfo());
			exit;
		}





	}
}else {
	print_r($error_info=$db->errorInfo());
	exit;
}






?>
