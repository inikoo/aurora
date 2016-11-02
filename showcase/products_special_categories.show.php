<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 February 2016 at 13:59:10 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_products_special_categories_showcase($data, $smarty) {


    $store = $data['_object'];
    if (!$store->id) {
        return "";
    }

    $smarty->assign('store', $store);

    return $smarty->fetch('showcase/products_special_categories.tpl');


}


?>