<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2016 at 16:18:43 BST, Sheffield, UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_part_family_showcase($data, $smarty) {



	$category=$data['_object'];
	if (!$category->id) {
		return "";
	}

	$smarty->assign('category', $category);

	$images=$category->get_images_slidesshow();

	if (count($images)>0) {
		$main_image=$images[0];
	}else {
		$main_image='';
	}


	$smarty->assign('main_image', $main_image);
	$smarty->assign('images', $images);

	return $smarty->fetch('showcase/part_family.tpl');



}


?>
