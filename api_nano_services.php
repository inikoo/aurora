<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 4 December 2018 at 13:26:28 GMT+88, Kuala Lumpur, Malaysia

 Version 2.0
*/

$account = get_object('Account', 1);


if (empty($_REQUEST['action'])) {
    $response = log_api_key_access_failure(
        $db, $api_key_key, 'Fail_Operation', "Action missing"
    );
    echo json_encode($response);
    exit;
}


switch ($_REQUEST['action']) {


    case 'get_order_shipping':


        if(empty($_REQUEST['store_key'])){
            $response = array(
                'state' => 'ERROR',
                'msg'   => 'store key required'
            );
            echo json_encode($response);
            exit;
        }
        if(empty($_REQUEST['country'])){
            $response = array(
                'state' => 'ERROR',
                'msg'   => 'country code required'
            );
            echo json_encode($response);
            exit;
        }


        $store = get_object('Store', $_REQUEST['store_key']);
        $shipping_zone_schema_key = $store->properties['current_shipping_zone_schema'];


        $_data = array(
            'shipping_zone_schema_key'  => $shipping_zone_schema_key,

            'Order Data' => array(
                'Order Items Net Amount'                      => (isset($_REQUEST['items_net_amount'])?$_REQUEST['items_net_amount']:0),
                'Order Delivery Address Postal Code'          => (isset($_REQUEST['postal_code'])?$_REQUEST['postal_code']:''),
                'Order Delivery Address Country 2 Alpha Code' => (isset($_REQUEST['country'])?$_REQUEST['country']:''), $_REQUEST['country'],
            )


        );

        include_once 'nano_services/shipping_for_order.ns.php';

        $shipping_data = (new shipping_for_order($db))->get($_data);

        if($shipping_data){
            $response = array(
                'state' => 'OK',
                'data'  => $shipping_data
            );
        }else{
            $response = array(
                'state' => 'ERROR',
                'msg'   => 'store not found'
            );
        }


        echo json_encode($response);
        exit;

        break;

    default:


        $response = array(
            'state' => 'Error',
            'msg'   => "Action ".$_REQUEST['action'].' not found'
        );
        echo json_encode($response);
        exit;


        //$response = log_api_key_access_failure($db, $api_key_key, 'Fail_Operation', "Action ".$_REQUEST['action'].' not found');
        echo json_encode($response);
        exit;

}

?>
