<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 August 2018 at 16:35:36 GMT+8, Legian , Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/date_functions.php';


function get_invoice_category_showcase($data, $smarty) {


    $category = $data['_object'];


    if (!$category->id) {
        return "";
    }

    $category->load_acc_data();

    $smarty->assign('category', $category);


    return $smarty->fetch('showcase/invoice_category.tpl');


}



