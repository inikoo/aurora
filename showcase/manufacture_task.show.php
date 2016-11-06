<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 January 2016 at 17:59:28 GMT+8, Kuala Lumpur, Malaysis

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_manufacture_task_showcase($data, $smarty) {


    $manufacture_task = $data['_object'];
    if (!$manufacture_task->id) {
        return "";
    }

    $smarty->assign('manufacture_task', $manufacture_task);

    return $smarty->fetch('showcase/manufacture_task.tpl');


}


?>