<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 Jul 2021 02:36 Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 3.0
*/

include_once 'utils/date_functions.php';


function get_customer_category_showcase($data, $smarty) {


    $category = $data['_object'];


    if (!$category->id) {
        return "";
    }

    $category->load_acc_data();

    $smarty->assign('category', $category);


    return $smarty->fetch('showcase/customer_category.tpl');


}



