<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2015 16:54:39 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_customer_showcase($data, $smarty, $user, $db, $redis, $account) {


    include_once 'utils/real_time_functions.php';
    $customer = $data['_object'];
    if (!$customer->id) {
        return "";
    }


    if ($customer->deleted) {
        $smarty->assign('customer', $customer);

        return $smarty->fetch('showcase/deleted_customer.tpl');

    } else {

        $customer->update_account_balance();
        $customer->update_credit_account_running_balances();
        $customer->update_portfolio();

        //$customer->update_orders();
        //$customer->update_last_dispatched_order_key();
        //$customer->update_invoices();
        //$customer->update_payments();
        //$customer->update_activity();


        //$customer->update_clients_data();

        $smarty->assign('customer', $customer);
        $smarty->assign('store', $data['store']);

        $website_key = $data['store']->get('Store Website Key');

        $customer_web_info_logged_in = '<table class="customer_real_data_info">
                <td class="device_label" ></td>
                <td class="user_location"></td>
                <td class="webpage_label"></td>
                </table>';

        $online          = false;
        $real_time_users = $redis->ZREVRANGE('_WU'.$account->get('Code').'|'.$website_key, 0, 1000, true);
        foreach ($real_time_users as $_key => $timestamp) {

            $_customer_key = preg_replace('/^.*\|/', '', $_key);

            if ($_customer_key == $customer->id) {

                $website_user = json_decode($redis->get($_key), true);

                if (is_array($website_user)) {
                    $online                      = true;
                    $customer_web_info_logged_in = '<table class="customer_real_data_info">
                <td class="device_label" >'.$website_user['device_label'].'</td>
                <td class="user_location">'.$website_user['location'].'</td>
                <td class="webpage_label">'.$website_user['webpage_label'].'</td>
                </table>';


                }


                break;
            }


        }
        $customer_web_info_log_out = '<span class="italic discreet">'._('Customer not logged in').'</span>';

        $last_visit_date = $customer->get('Last Website Visit');
        if ($last_visit_date != '') {
            $customer_web_info_log_out .= ' <span class="small italic discreet padding_left_5">('.sprintf('Last seen %s', $last_visit_date).')</span>';

        }


        $smarty->assign('online', $online);

        $smarty->assign('customer_web_info_logged_in', $customer_web_info_logged_in);
        $smarty->assign('customer_web_info_log_out', $customer_web_info_log_out);

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
        $sql               = "select `Order Key` from `Order Dimension` where `Order Customer Key`=? and !(`Order State` ='Dispatched' or `Order State` ='Cancelled')  order by `Order Submitted by Customer Date`  ";
        $stmt              = $db->prepare($sql);
        $stmt->execute(
            array($customer->id)
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


        return $smarty->fetch('showcase/customer.tpl');


    }


}