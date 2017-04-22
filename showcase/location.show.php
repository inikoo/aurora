<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 17:14:25 GMT+8, Lovina, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_location_showcase($data, $smarty, $user, $db) {


    $location = $data['_object'];
    $smarty->assign('location', $location);




    if ($location->deleted) {
        return $smarty->fetch('showcase/deleted_location.tpl');
    } else {
        return $smarty->fetch('showcase/location.tpl');
    }

}

function get_locked_location_showcase($data, $smarty, $user, $db) {


    $smarty->assign('location', $data['_object']);

    return $smarty->fetch('showcase/location.locked.tpl');

}

?>
