<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 October 2016 at 14:32:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Agent.php';
require_once 'class.Supplier.php';
require_once 'class.Category.php';





$sql=sprintf('select `Agent Key` from `Agent Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$agent=new Agent($row['Agent Key']);
		$agent->load_acc_data();
		$agent->update_sales('Total');
		$agent->update_sales('Week To Day');
		$agent->update_sales('Month To Day');
		$agent->update_sales('Quarter To Day');
		$agent->update_sales('Year To Day');
		$agent->update_sales('1 Year');
		$agent->update_sales('1 Quarter');
	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}


$sql=sprintf('select `Supplier Key` from `Supplier Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$supplier=new Supplier($row['Supplier Key']);

		$supplier->load_acc_data();
		$supplier->update_sales('Total');
		$supplier->update_sales('Week To Day');
		$supplier->update_sales('Month To Day');
		$supplier->update_sales('Quarter To Day');
		$supplier->update_sales('Year To Day');
		$supplier->update_sales('1 Year');
		$supplier->update_sales('1 Quarter');

	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}

$sql=sprintf("select `Category Key` from `Category Dimension` where   `Category Scope`='Supplier'  ");
if ($result=$db->query($sql)) {
	foreach ($result as $row) {



		$category=new Category($row['Category Key']);

		//print $category->id."\n";
		$category->update_supplier_category_sales('Total');
		$category->update_supplier_category_sales('Week To Day');
		$category->update_supplier_category_sales('Month To Day');
		$category->update_supplier_category_sales('Quarter To Day');
		$category->update_supplier_category_sales('Year To Day');
		$category->update_supplier_category_sales('1 Year');
		$category->update_supplier_category_sales('1 Quarter');

	}
}


?>
