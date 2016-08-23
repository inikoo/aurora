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


$sql=sprintf('select `Part SKU` from `Part Dimension` order by `Part SKU`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$part=new Part($row['Part SKU']);
		print $part->sku."\r";
		$part->update_cost();




	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}



?>
