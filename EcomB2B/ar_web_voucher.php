<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 March 2019 at 15:28:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';

$account = get_object('Account', 1);

$website = get_object('Website', $_SESSION['website_key']);


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];

switch ($tipo) {


    case 'update_voucher':
        $data = prepare_values(
            $_REQUEST, array(
                         'voucher' => array('type' => 'string'),
                     )
        );

        update_voucher($data, $customer, $order, $editor, $db);


        break;


}


function update_voucher($_data, $customer, $order, $editor, $db) {


    $current_voucher_keys = $order->get_vouchers();
    $voucher_code         = trim($_data['voucher']);
    $error                = false;
    $fail_code            = '';


    if($order->get('Order State')=='InBasket'){
        $order->fast_update(
            array(
                'Order Last Updated by Customer'   => gmdate('Y-m-d H:i:s')
            )
        );
    }


    if ($voucher_code == '') {
        if (count($current_voucher_keys) == 0) {

            $response = array(
                'state'    => 200,
                'metadata' => array(
                    'class_html' => array()
                )
            );
            echo json_encode($response);

            return;
        } else {
            $sql = "delete from `Voucher Order Bridge` where `Order Key`=? ";
            $db->prepare($sql)->execute(
                [
                    $order->id,
                ]
            );
            $old_used_deals=$order->get_used_deals();
            $order->update_discounts_items();
            $order->update_totals();
            $order->update_shipping();
            $order->update_charges();
            $order->update_discounts_no_items();
            $order->update_deal_bridge();

            $new_used_deals=$order->get_used_deals();
            $intersect = array_intersect($old_used_deals[0], $new_used_deals[0]);
            $campaigns_diff =array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

            $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
            $deal_diff =array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));

            $intersect = array_intersect($old_used_deals[2], $new_used_deals[2]);
            $deal_components_diff =array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));



            if(count($campaigns_diff)>0 or count($deal_diff)>0  or count($deal_components_diff)>0 ){
                $account = get_object('Account', '');

                require_once 'utils/new_fork.php';
                new_housekeeping_fork(
                    'au_housekeeping', array(
                    'type'      => 'update_deals_usage',
                    'campaigns' => $campaigns_diff,
                    'deals' => $deal_diff,
                    'deal_components' => $deal_components_diff,


                ), $account->get('Account Code'), $db
                );
            }

            $order->update_totals();

            $response = array(
                'state'    => 200,
                'action'   => 'deleted',
                'metadata' => array(
                    'class_html' => array()
                )
            );
            echo json_encode($response);
            exit;

        }
    }


    $sql = "select V.`Voucher Key` ,D.`Deal Key`  ,`Deal Status` from `Voucher Dimension` V   left join `Deal Dimension` D on (V.`Voucher Key`=`Deal Voucher Key`) where `Voucher Store Key`=? and `Voucher Code`=? ";

    $stmt = $db->prepare($sql);
    if ($stmt->execute(
        array(
            $customer->get('Store Key'),
            $voucher_code
        )
    )) {
        if ($row = $stmt->fetch()) {


            if ($row['Deal Status'] == 'Active') {
                $voucher_keys = $order->get_vouchers();


                if (in_array($row['Voucher Key'], $voucher_keys)) {
                    $response = array(
                        'state'    => 200,
                        'action'   => 'none',
                        'metadata' => array(
                            'class_html' => array()
                        )
                    );
                } else {

                    $sql = "delete from `Voucher Order Bridge` where `Order Key`=? ";
                    $db->prepare($sql)->execute(
                        [
                            $order->id,
                        ]
                    );

                }


                $sql = "INSERT INTO `Voucher Order Bridge` (`Voucher Key`, `Order Key`, `Date`,`Deal Key`,`Customer Key`,`State`) VALUES (?,?,?,?,?,?)";
                $db->prepare($sql)->execute(
                    [
                        $row['Voucher Key'],
                        $order->id,
                        gmdate('Y-m-d H:i:s'),
                        $row['Deal Key'],
                        $customer->id,
                        'In Process'
                    ]
                );

                $old_used_deals=$order->get_used_deals();
                $order->update_discounts_items();
                $order->update_totals();
                $order->update_shipping();
                $order->update_charges();
                $order->update_discounts_no_items();
                $order->update_deal_bridge();

                $new_used_deals=$order->get_used_deals();
                $intersect = array_intersect($old_used_deals[0], $new_used_deals[0]);
                $campaigns_diff =array_merge(array_diff($old_used_deals[0], $intersect), array_diff($new_used_deals[0], $intersect));

                $intersect = array_intersect($old_used_deals[1], $new_used_deals[1]);
                $deal_diff =array_merge(array_diff($old_used_deals[1], $intersect), array_diff($new_used_deals[1], $intersect));

                $intersect = array_intersect($old_used_deals[2], $new_used_deals[2]);
                $deal_components_diff =array_merge(array_diff($old_used_deals[2], $intersect), array_diff($new_used_deals[2], $intersect));



                if(count($campaigns_diff)>0 or count($deal_diff)>0  or count($deal_components_diff)>0 ){
                    $account = get_object('Account', '');

                    require_once 'utils/new_fork.php';
                    new_housekeeping_fork(
                        'au_housekeeping', array(
                        'type'      => 'update_deals_usage',
                        'campaigns' => $campaigns_diff,
                        'deals' => $deal_diff,
                        'deal_components' => $deal_components_diff,


                    ), $account->get('Account Code'), $db
                    );
                }

                $order->update_totals();

                $response = array(
                    'state'    => 200,
                    'action'   => 'add',
                    'metadata' => array(
                        'class_html' => array()
                    )
                );
                echo json_encode($response);
                exit;


            } else {
                $fail_code = 'not_expired';
                $error     = true;
            }


        } else {

            $fail_code = 'not_found';
            $error     = true;

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit();
    }


    if ($error) {
        switch ($fail_code) {
            case 'expired':
                $msg = _('Voucher expired');
                break;
            case 'not_found':
                $msg = _('Voucher not found');
                break;
            default:
                $msg = _('Error');

        }

        $response = array(
            'state'    => 400,
            'msg'      => $msg,
            'metadata' => array(
                'class_html' => array()
            )
        );
    }


    echo json_encode($response);

}




