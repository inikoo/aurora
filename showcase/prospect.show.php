<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2018 at 14:31:51 GMT+8

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_prospect_showcase($data, $smarty) {


    $prospect = $data['_object'];
    if (!$prospect->id) {
        return "";
    }

    $smarty->assign('prospect', $prospect);

    return $smarty->fetch('showcase/prospect.tpl');


}


