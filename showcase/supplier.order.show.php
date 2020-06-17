<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 13:57:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_supplier_order_showcase($data, $smarty, $user) {

    /**
     * @var $purchase_order \PurchaseOrder
     */
    $purchase_order = $data['_object'];

    if (!$purchase_order->id) {
        return "";
    }

    if ($purchase_order->deleted) {

        return '';

    } else {


        $smarty->assign('no_production_date_label', _('No estimated production date'));

        $smarty->assign('order', $purchase_order);


        //foreach($purchase_order->get_deliveries('objects')  as $delivery){
        //    $delivery->update_supplier_delivery_items_state();
        //}
      //  $purchase_order->update_purchase_order_items_state();


        $_parent = get_object(
            $purchase_order->get('Purchase Order Parent'), $purchase_order->get('Purchase Order Parent Key')
        );

        $smarty->assign('parent', $_parent);


        $smarty->assign(
            'object_data', json_encode(
                             array(
                                 'object'                  => $data['object'],
                                 'key'                     => $data['key'],
                                 'order_parent'            => $purchase_order->get('Purchase Order Parent'),
                                 'order_parent_key'        => $purchase_order->get('Purchase Order Parent Key'),
                                 'tab'                     => $data['tab'],
                                 'purchase_order_number'   => $purchase_order->get('Purchase Order Public ID'),
                                 'skip_inputting'          => $_parent->get('Parent Skip Inputting'),
                                 'skip_mark_as_dispatched' => $_parent->get('Parent Skip Mark as Dispatched'),
                                 'skip_mark_as_received'   => $_parent->get('Parent Skip Mark as Received'),
                                 'skip_checking'           => $_parent->get('Parent Skip Checking'),
                                 'type'                    => $purchase_order->get('Purchase Order Type'),


                             )
                         )

        );




        if ($purchase_order->get('Purchase Order Submitted Date') != '') {
            $min_date_send_order = date(
                'U', strtotime(
                       $purchase_order->get('Purchase Order Submitted Date').' +0:00'
                   )
            );
        } else {
            $min_date_send_order = date(
                'U', strtotime(
                       $purchase_order->get('Purchase Order Created Date').' +0:00'
                   )
            );


        }
        $smarty->assign('min_date_send_order', 1000 * $min_date_send_order);


        if ($user->get('User Type') == 'Staff' or $user->get('User Type') == 'Contractor') {

            if ($purchase_order->get('Purchase Order Production') == 'Yes') {
                return $smarty->fetch('showcase/production.purchase_order.tpl');

            } else {
                return $smarty->fetch('showcase/supplier.order.tpl');
            }

        } elseif ($user->get('User Type') == 'Agent') {
            return $smarty->fetch('showcase/client_order.tpl');
        }
    }

}



