<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 February 2016 at 17:33:42 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_department_showcase($data,$smarty) {

   
    
    $category=$data['_object'];
    if(!$category->id){
        return "";
    }
    
 	$images=$category->get_images_slidesshow();

	if (count($images)>0) {
		$main_image=$images[0];
	}else {
		$main_image='';
	}


	$smarty->assign('main_image', $main_image);
	$smarty->assign('images', $images);


    
    $smarty->assign('category',$category);

    return $smarty->fetch('showcase/department.tpl');
    


}


?>