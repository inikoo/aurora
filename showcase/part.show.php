<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2016 at 23:50:31 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_part_showcase($data,$smarty,$user,$db) {



	$part=$data['_object'];
	
//	$part->update_cost();
//	$part->updated_linked_products();



	//$part->fix_stock_transactions();
	
	
	
	
	if (!$part->id) {
		return "";
	}

/*
	$images=$part->get_images_slidesshow();

	if (count($images)>0) {
		$main_image=$images[0];
	}else {
		$main_image='';
	}


	$smarty->assign('main_image', $main_image);
	$smarty->assign('images', $images);
*/
	


	$smarty->assign('part', $part);

	return $smarty->fetch('showcase/part.tpl');



}


?>
