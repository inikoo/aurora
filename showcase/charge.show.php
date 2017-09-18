<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 September 2017 at 15:17:51 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_charge_showcase($data, $smarty) {


    $charge = $data['_object'];

    $charge->update_charge_usage();

    if (!$charge->id) {
        return "";
    }

   

    $smarty->assign('charge', $charge);

    return $smarty->fetch('showcase/charge.tpl');


}


?>