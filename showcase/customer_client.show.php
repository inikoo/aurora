<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 3 Oct 2019 14:37:35 +0800 MYT, Kuala Lumpur

Copyright (c) 2019, Inikoo

 Version 3.0
*/

function get_customer_client_showcase($data, $smarty, $user, $db, $redis, $account) {


    $customer_client = $data['_object'];
    if (!$customer_client->id) {
        return "";
    }

    $customer = get_object('Customer', $customer_client->get('Customer Client Customer Key'));

    $smarty->assign('customer_client', $customer_client);

    $smarty->assign('customer', $customer);
    $smarty->assign('store', $data['store']);

    $order_basket      = array(
        'key'             => '',
        'public_id'       => '<span class="very_discreet italic">'._('No order in basket').'</span>',
        'number_items'    => '',
        'weight'          => '',
        'items_net'       => '',
        'other_net'       => '',
        'tax'             => '',
        'last_modify'     => '',
        'total'           => '',
        'tax_description' => '',
    );
    $orders_in_process = array();
    $sql               = "select `Order Key` from `Order Dimension` where `Order Customer Client Key`=? and !(`Order State` ='Dispatched' or `Order State` ='Cancelled')  order by `Order Submitted by Customer Date`  ";
    $stmt              = $db->prepare($sql);
    $stmt->execute(
        array($customer_client->id)
    );
    while ($row = $stmt->fetch()) {


        $order = get_object('order', $row['Order Key']);
        if ($order->get('Order State') == 'InBasket') {

            $order_basket['key']             = $order->id;
            $order_basket['public_id']       = '<span class="button" onclick="change_view(\'customer/'.$customer->id.'/order/'.$order->id.'\')" >'.$order->get('Order Public ID').'</span>';
            $order_basket['number_items']    = '<i class="fa fa-cube"></i> '.$order->get('Number Items');
            $order_basket['weight']          = '<span title="'._('Estimated weight').'" class=" Estimated_Weight">'.$order->get('Estimated Weight').'</span>';
            $order_basket['items_net']       = $order->get('Items Net Amount');
            $order_basket['other_net']       = money($order->get('Order Shipping Net Amount') + $order->get('Order Charges Net Amount'), $order->get('Order Currency'));
            $order_basket['tax']             = $order->get('Total Tax Amount');
            $order_basket['total']           = $order->get('Total Amount');
            $order_basket['tax_description'] = $order->get('Tax Description');
            $order_basket['last_modify']     = $order->get('Last Updated by Customer');

        } else {
            $order_in_process = array();

            switch ($order->get('Order State')) {
                case 'InProcess':
                    $order_in_process['icon']       = sprintf('<i class="fal fa-paper-plane" title="%s"></i>', $order->get('State'));
                    $order_in_process['operations'] = '';
                    break;
                case 'InWarehouse':
                    $order_in_process['operations'] = '';
                    $order_in_process['icon']       = sprintf('<i class="fal fa-warehouse-alt" title="%s"></i>', $order->get('Order State'));
                    break;
                default:
                    $order_in_process['operations'] = '';
                    $order_in_process['icon']       = sprintf('<i class="fal fa-warehouse-alt" title="%s"></i>', $order->get('Order State'));
                    break;
            }


            $order_in_process['key']             = $order->id;
            $order_in_process['public_id']       = '<span class="button" onclick="change_view(\'customer/'.$customer->id.'/order/'.$order->id.'\')" >'.$order->get('Order Public ID').'</span>';
            $order_in_process['number_items']    = '<i class="fa fa-cube"></i> '.$order->get('Number Items');
            $order_in_process['weight']          = '<span title="'._('Estimated weight').'" class=" Estimated_Weight">'.$order->get('Estimated Weight').'</span>';
            $order_in_process['items_net']       = $order->get('Items Net Amount');
            $order_in_process['other_net']       = money($order->get('Order Shipping Net Amount') + $order->get('Order Charges Net Amount'), $order->get('Order Currency'));
            $order_in_process['tax']             = $order->get('Total Tax Amount');
            $order_in_process['total']           = $order->get('Total Amount');
            $order_in_process['tax_description'] = $order->get('Tax Description');
            $order_in_process['submitted']       = $order->get('Submitted by Customer Date');
            $order_in_process['waiting_days']    = $order->get('Waiting days decimal');

            $order_in_process['state'] = $order->get('State');

            $orders_in_process[] = $order_in_process;
        }
    }


    $smarty->assign('order_basket', $order_basket);
    $smarty->assign('orders_in_process', $orders_in_process);

    return $smarty->fetch('showcase/customer_client.tpl');


}


