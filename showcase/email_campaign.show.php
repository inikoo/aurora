<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2017 at 18:28:52 GMT+8, Kuala umpur, Malaydia

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_email_campaign_showcase($data, $smarty, $user, $db) {


    if (!$data['_object']->id) {
    }


    $email_campaign = $data['_object'];

    if ($email_campaign->get('State Index') <= 40) {
        $email_campaign->update_estimated_recipients();
    }

    //$email_campaign->update_sent_emails_totals();



    $smarty->assign('email_campaign', $email_campaign);

    $smarty->assign('store', get_object('store', $email_campaign->get('Store Key')));


    $smarty->assign(
        'object_data',
                         json_encode(
                             array(
                                 'object' => $data['object'],
                                 'key'    => $data['key'],
                                 'tab' => $data['tab']
                             )
                         )

    );


    switch ($email_campaign->get('Email Campaign Type')) {
        case 'AbandonedCart':
        case 'Newsletter':
        case 'Invite Full Mailshot':
        case 'Marketing':
            return $smarty->fetch('showcase/email_campaign.tpl');
            break;


    }


}

