<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 February 2019 at 13:49:40 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_deal_component_showcase($data, $smarty) {


    $deal_component = $data['_object'];
    if (!$deal_component->id) {
        return "";
    }


    $smarty->assign('deal_component', $deal_component);

    return $smarty->fetch('showcase/deal_component.tpl');


}


?>