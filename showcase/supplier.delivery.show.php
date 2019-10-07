<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 July 2016 at 01:09:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_showcase($data, $smarty, $user, $db) {


    if (!$data['_object']->id) {
        return "";
    }

    $data['_object']->get_order_data();

    /*
    $data['_object']->update_supplier_delivery_items_state();
    */

    $_parent = get_object($data['_object']->get('Supplier Delivery Parent'), $data['_object']->get('Supplier Delivery Parent Key'));

    $smarty->assign('parent', $_parent);


    if ($data['_object']->get('Supplier Delivery Parent') == 'Order') {


        $smarty->assign('order', $_parent);
        $smarty->assign('return', $data['_object']);

        $smarty->assign(
            'object_data', json_encode(
                             array(
                                 'object'           => $data['object'],
                                 'key'              => $data['key'],
                                 'order_parent'     => $data['_object']->get('Supplier Delivery Parent'),
                                 'order_parent_key' => $data['_object']->get('Supplier Delivery Parent Key'),


                             )
                         )

        );

        return $smarty->fetch('showcase/return.tpl');

    } else {

        $smarty->assign('delivery', $data['_object']);
        $smarty->assign('parent', $_parent);

        $smarty->assign(
            'object_data', json_encode(
                             array(
                                 'object'           => $data['object'],
                                 'key'              => $data['key'],
                                 'order_parent'     => $data['_object']->get('Supplier Delivery Parent'),
                                 'order_parent_key' => $data['_object']->get('Supplier Delivery Parent Key'),

                                 'skip_inputting'          => $_parent->get('Parent Skip Inputting'),
                                 'skip_mark_as_dispatched' => $_parent->get('Parent Skip Mark as Dispatched'),
                                 'skip_mark_as_received'   => $_parent->get('Parent Skip Mark as Received'),
                                 'skip_checking'           => $_parent->get('Parent Skip Checking'),


                             )
                         )

        );


        if ($data['_object']->get('Purchase Order Submitted Date') != '') {
            $min_date_send_order = 1000 * date('U', strtotime($data['_object']->get('Purchase Order Submitted Date').' +0:00'));
        } else {
            $min_date_send_order = '';


        }
        $smarty->assign('min_date_send_order', $min_date_send_order);


        if ($data['_object']->get('Supplier Delivery Production') == 'Yes') {
            return $smarty->fetch('showcase/production.delivery.tpl');

        } else {
            return $smarty->fetch('showcase/supplier.delivery.tpl');
        }

    }


}



