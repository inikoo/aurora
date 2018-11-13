<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2018 at 19:07:23 GMT+8, Kuala umpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 3.0
*/

function get_purge_showcase($data, $smarty, $user, $db) {


    if (!$data['_object']->id) {
    }


    $purge = $data['_object'];


    if ($purge->get('State Index') <= 20) {
        $purge->update_estimated_orders_to_be_purged();
    }

    //$purge->update_sent_emails_totals();

    $smarty->assign('purge', $purge);

    $smarty->assign('store', get_object('store', $purge->get('Store Key')));


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

    return $smarty->fetch('showcase/purge.tpl');


}


?>
