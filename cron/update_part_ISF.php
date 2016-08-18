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



//$where=' `Part SKU`=50264';
$where='  true';

$count=0;
$sql=sprintf('select `Part SKU` from `Part Dimension` where %s  order by `Part SKU` desc', $where);

// print "$sql\n";

if ($result2=$db->query($sql)) {
	foreach ($result2 as $row2) {


		$part=new Part($row2['Part SKU']);

		$from=$part->get('Part Valid From');
		$to=($part->get('Part Status')=='Not In Use'?$part->get('Part Valid To'):gmdate('Y-m-d H:i:s'));


		$sql=sprintf("delete from `Inventory Spanshot Fact` where `Part SKU`=%d  and (`Date`<%s  or `Date`>%s  )",
			$part->sku,
			prepare_mysql($from),
			prepare_mysql($to)
		);
		$db->exec($sql);


		//$from='2016-03-18';
		//$to='2016-03-18';
		$sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=date(%s) and `Date`<=date(%s) order by `Date` desc",
			prepare_mysql($from), prepare_mysql($to));



		if ($result=$db->query($sql)) {
			foreach ($result as $row) {


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







?>
