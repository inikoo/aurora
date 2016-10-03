<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2016 at 18:06:32 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';

$print_est=true;

update_parts_sales($db, $print_est);
//update_categories_sales($db, $print_est);

function update_parts_sales($db, $print_est) {

	$where=" where `Part SKU`=971 ";
	$where="where true";
	//$where=" where `Part Reference` like 'jbb-%' ";

	$sql=sprintf("select count(*) as num from `Part Dimension` %s",$where);
	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {
			$total=$row['num'];
		}else {
			$total=0;
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$lap_time0=date('U');
	$contador=0;

	$sql=sprintf("select `Part SKU` from `Part Dimension`  %s  order by `Part SKU`",$where);

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$part=new Part($row['Part SKU']);




			$part->load_acc_data();

			$part->update_previous_quarters_data();
			

			$contador++;
			$lap_time1=date('U');

			if ($print_est) {
				print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h  ($contador/$total) \r";
			}


		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}
}




?>
