<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 November 2018 at 17:31:45 GMT+8

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_warehouse_area_showcase($data, $smarty, $user, $db) {


    $smarty->assign('warehouse_area', $data['_object']);


    return $smarty->fetch('showcase/warehouse_area.tpl');


}

function get_locked_warehouse_area_showcase($data, $smarty, $user, $db) {


    $smarty->assign('warehouse_area', $data['_object']);

    return $smarty->fetch('showcase/warehouse_area.locked.tpl');

}

?>
