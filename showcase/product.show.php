<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 February 2016 at 14:12:03 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_product_showcase($data,$smarty,$user,$db) {



	$product=$data['_object'];
	if (!$product->id) {
		return "";
	}

	$images=$product->get_images_slidesshow();

	if (count($images)>0) {
		$main_image=$images[0];
	}else {
		$main_image='';
	}


	$smarty->assign('main_image', $main_image);
	$smarty->assign('images', $images);

	$sql=sprintf("select `Category Label`,`Category Code`,`Category Key` from `Category Dimension` where `Category Key`=%d ",
		$product->get('Store Product Department Category Key'));

	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {
			$department_data=array(
				'id'=>$row['Category Key'],
				'code'=>$row['Category Code'],
				'label'=>$row['Category Label'],
			);
		}else {
			$department_data=array('id'=>false);
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$sql=sprintf("select `Category Label`,`Category Code`,`Category Key` from `Category Dimension` where `Category Key`=%d ",
		$product->get('Store Product Family Category Key'));
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

	$smarty->assign('product', $product);
	$smarty->assign('department_data', $department_data);
	$smarty->assign('family_data', $family_data);

	return $smarty->fetch('showcase/product.tpl');



}


?>
