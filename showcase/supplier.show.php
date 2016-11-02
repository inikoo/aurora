<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 February 2016 at 19:28:04 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_supplier_showcase($data) {

    global $smarty;

    $supplier = $data['_object'];
    if (!$supplier->id) {
        return "";
    }

    $smarty->assign('supplier', $supplier);

    if ($supplier->deleted) {
        return $smarty->fetch('showcase/deleted_supplier.tpl');
    } else {
        return $smarty->fetch('showcase/supplier.tpl');
    }

}


?>
