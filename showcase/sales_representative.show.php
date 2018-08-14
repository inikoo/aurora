<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 August 2018 at 14:12:27 GMT+8, Kuta, Bali, Indonesia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_sales_representative_showcase($data) {

    global $smarty;


    if (!$data['_object']->id) {
        return "";
    }

    $smarty->assign('sales_representative', $data['_object']);

    return $smarty->fetch('showcase/sales_representative.tpl');


}


?>