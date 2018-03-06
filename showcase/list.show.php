<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 March 2018 at 13:09:29 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_list_showcase($data, $smarty) {


    $list = $data['_object'];


    if (!$list->id) {
        return "";
    }


    $smarty->assign('list', $list);

    return $smarty->fetch('showcase/list.tpl');


}


?>