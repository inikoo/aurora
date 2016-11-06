<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 November 2015 at 15:21:51 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_employee_showcase($data, $smarty, $user, $db) {


    if (!$data['_object']->id) {
        return "";
    }


    $smarty->assign('employee', $data['_object']);

    if ($data['_object']->deleted) {
        return $smarty->fetch('showcase/deleted_employee.tpl');

    } else {

        return $smarty->fetch('showcase/employee.tpl');
    }


}


?>