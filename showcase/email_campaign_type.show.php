<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 May 2018 at 15:05:45 CEST, Trnava, Slovakia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_email_campaign_type_showcase($data, $smarty, $user, $db) {

    if (!$data['_object']->id) {
        return "";
    }


    $email_campaign_type = $data['_object'];


  


    $smarty->assign('email_campaign_type', $email_campaign_type);

    $smarty->assign('store', get_object('store', $email_campaign_type->get('Store Key')));


    $smarty->assign(
        'object_data', base64_encode(
                         json_encode(
                             array(
                                 'object' => $data['object'],
                                 'key'    => $data['key'],

                                 'tab' => $data['tab']
                             )
                         )
                     )
    );


    switch ($email_campaign_type->get('Email Campaign Type Code')) {
        default:
            return $smarty->fetch('showcase/email_campaign_type.tpl');
            break;


    }


}


?>
