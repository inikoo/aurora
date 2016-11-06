<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 10:19:15 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_order_showcase($data, $smarty, $user, $db) {


    if (!$data['_object']->id) {
        return "";
    }

    $smarty->assign('order', $data['_object']);

    $order = $data['_object'];

    $order->update_totals();


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


    return $smarty->fetch('showcase/order.tpl');


}


?>
