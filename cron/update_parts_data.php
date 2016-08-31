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
require_once 'class.Category.php';

update_parts($db);
update_categories($db);

function update_parts($db) {

	$sql=sprintf('select `Part SKU` from `Part Dimension` where `Part SKU`=5285 ');
	$sql=sprintf('select `Part SKU` from `Part Dimension` order by `Part SKU`  ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$part=new Part($row['Part SKU']);

			$part->update_up_today_sales();
			$part->update_last_period_sales();
			$part->update_interval_sales();
			$part->update_previous_years_data();

		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}
}


function update_categories($db) {
	$sql=sprintf("select `Category Key`  from `Category Bridge` where  `Subject`='Part' group by `Category Key` ");
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$category=new Category($row['Category Key']);
			$category->update_number_of_subjects();
			$category->update_subjects_data();
			$category->update_part_category_previous_years_data();
			$category->update_part_stock_status();
		}
	}
}


?>
