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
	

	$sql=sprintf("select `Category Label`,`Category Code`,`Category Key` from `Category Dimension` where `Category Key`=%d ",
		$part->get('Part Family Category Key'));
	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {
			$family_data=array(
				'id'=>$row['Category Key'],
				'code'=>$row['Category Code'],
				'label'=>$row['Category Label'],
			);
		}else {
			$family_data=array('id'=>false);
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$smarty->assign('part', $part);
	$smarty->assign('family_data', $family_data);

	return $smarty->fetch('showcase/part.tpl');



}


?>
