<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 November 2015 at 21:57:02 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_contractor_showcase($data) {

    global $smarty;


    if (!$data['_object']->id) {
        return "";
    }

    $smarty->assign('contractor', $data['_object']);

    return $smarty->fetch('showcase/contractor.tpl');


}


?>