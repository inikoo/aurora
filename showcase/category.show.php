<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 26 May 2016 at 15:58:24 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_category_showcase($data, $smarty) {


    $category = $data['_object'];

    if (!$category->id) {
        return "";
    }

    $images = $category->get_images_slidesshow();

    if (count($images) > 0) {
        $main_image = $images[0];
    } else {
        $main_image = '';
    }


    $smarty->assign('category', $category);

    return $smarty->fetch('showcase/category.tpl');


}


?>