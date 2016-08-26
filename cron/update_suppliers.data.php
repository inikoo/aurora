<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2016 at 14:18:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.Product.php';
require_once 'class.Page.php';
require_once 'class.Supplier.php';
require_once 'class.Agent.php';



$sql=sprintf('select `Agent Key` from `Agent Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$agent=new Agent($row['Agent Key']);
		$agent->update_supplier_parts();
		


	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}


$sql=sprintf('select `Supplier Key` from `Supplier Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$supplier=new Supplier($row['Supplier Key']);
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
