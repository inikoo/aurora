<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2016 at 11:41:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';


update_parts_cost($db);

function update_parts_cost($db) {

	$sql=sprintf('select `Part SKU` from `Part Dimension` where `Part Reference`="FO-P7" order by `Part SKU`   ');
	$sql=sprintf('select `Part SKU` from `Part Dimension`  order by `Part SKU`   ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$part=new Part($row['Part SKU']);

			$part->update_cost();


			if ($part->get('Part Cost')<=0) {
				print $part->get('Reference')." ".$part->get('Part Cost')."\n";


			}


		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}
}



?>
