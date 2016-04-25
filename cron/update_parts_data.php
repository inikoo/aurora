<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:19 April 2016 at 11:32:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Product.php';
require_once 'class.Page.php';
require_once 'class.Supplier.php';


/*

$sql=sprintf('select `Part SKU` from `Part Dimension` where `Part SKU`<261 ');
$sql=sprintf('select `Part SKU` from `Part Dimension` order by `Part SKU` desc ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$part=new Part($row['Part SKU']);

		$part->update_up_today_sales();
		$part->update_last_period_sales();
		$part->update_interval_sales();




	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}

*/


$sql=sprintf('select `Supplier Key` from `Supplier Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
	


	
		$supplier=new Supplier($row['Supplier Key']);
		
			$sql="insert into `Supplier Data` (`Supplier Key`) values(".$supplier->id.");";
			$db->exec($sql);

		$supplier->update_supplier_parts();
		$supplier->update_up_today_sales();
		$supplier->update_last_period_sales();
		$supplier->update_interval_sales();
$supplier->update_previous_years_data();


	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}

?>
