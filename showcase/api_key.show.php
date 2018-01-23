<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 January 2018 at 15:33:53 GMT+8, Kuala Lumpur, Malaysia
 
 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_api_key_showcase($data, $smarty) {


    $api_key = $data['_object'];
    if (!$api_key->id) {
        return "";
    }
    $staff_user=get_object('User',$api_key->get('API Key User Key'));

    $staff=get_object('Staff',$staff_user->get_staff_key());


    $smarty->assign('api_key', $api_key);
    $smarty->assign('staff_user', $staff_user);
    $smarty->assign('staff', $staff);

    $title=sprintf('%s API access key for %s',
                   sprintf('<span class="link">%s</span>',$staff->get('Name')),
                   $api_key->get('Scope')
    );
    $smarty->assign('title', $title);


    return $smarty->fetch('showcase/api_key.tpl');


}


?>