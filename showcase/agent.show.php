<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 11:01:05 GMT+8, Lovina, Ubud , Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_agent_showcase($data) {

    global $smarty;

    $agent = $data['_object'];
    if (!$agent->id) {
        return "";
    }

    $smarty->assign('agent', $agent);

    return $smarty->fetch('showcase/agent.tpl');


}


?>