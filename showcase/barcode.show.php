<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 April 2016 at 11:22:03 GMT+8,  Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_barcode_showcase($data, $smarty, $user, $db) {

    $barcode = $data['_object'];
    if (!$barcode->id) {
        return "";
    }

    $smarty->assign('barcode', $barcode);
    if ($barcode->deleted) {
        return $smarty->fetch('showcase/deleted_barcode.tpl');
    } else {
        return $smarty->fetch('showcase/barcode.tpl');
    }


}


?>
