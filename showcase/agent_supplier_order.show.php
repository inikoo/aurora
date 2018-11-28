<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 August 2018 at 17:35:58 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_agent_supplier_order_showcase($data, $smarty, $user, $db) {


    if (!$data['_object']->id) {
        return "";
    }

    if ($data['_object']->deleted) {

        return '';

    } else {

        $data['_object']->update_totals();

        $smarty->assign('order', $data['_object']);

        $_parent = get_object(
            'PurchaseOrder', $data['_object']->get('Agent Supplier Purchase Order Purchase Order Key')
        );

        $smarty->assign('parent', $_parent);


        $smarty->assign(
            'object_data',
            json_encode(
                array(
                    'object'                => $data['object'],
                    'key'                   => $data['key'],
                    'order_parent_key'      => $data['_object']->get('Agent Supplier Purchase Order Purchase Order Key'),
                    'tab'                   => $data['tab'],
                    'purchase_order_number' => $data['_object']->get('Agent Supplier Purchase Order Public ID'),


                )
            )

        );


        if ($data['_object']->get('Agent Supplier Purchase Order Confirm Date') != '') {
            $min_date_send_order = date(
                'U', strtotime(
                       $data['_object']->get('Agent Supplier Purchase Order Confirm Date').' +0:00'
                   )
            );
        } else {
            $min_date_send_order = date(
                'U', strtotime(
                       $data['_object']->get('Agent Supplier Purchase Order Created Date').' +0:00'
                   )
            );


        }
        $smarty->assign('min_date_send_order', 1000 * $min_date_send_order);


        return $smarty->fetch('showcase/agent_supplier_order.tpl');


    }

}


?>
