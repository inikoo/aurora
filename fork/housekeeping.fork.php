<?php
/*
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/



function fork_housekeeping($job) {


	if (!$_data=get_fork_metadata($job))
		return;


	list($account, $db, $data)=$_data;

	//print_r($data);

	switch ($data['type']) {

	case 'update_part_products_availability':
		
		include_once 'class.Part.php';
		$part=new Part($data['part_sku']);
		foreach ($part->get_products('objects') as $product) {
			$product->update_availability($use_fork=false);
		}

		break;

	
		

	}




	return false;
}


?>
