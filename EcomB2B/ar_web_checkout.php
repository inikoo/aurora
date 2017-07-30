<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 July 2017 at 16:44:58 CEST, Trnava, Slavakia
 Copyright (c) 2017, Inikoo

 Version 3

*/


require_once 'common.php';
require_once 'utils/ar_web_common.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


if (!$customer->id) {
    $response = array(
        'state' => 400,
        'resp'  => 'not customer'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'place_order_pay_later':
        $data = prepare_values(
            $_REQUEST, array(
                         'payment_account_key' => array('type' => 'key'),
                         'order_key'           => array('type' => 'key')

                     )
        );

        place_order_pay_later($data, $customer, $website, $editor);


        break;
}

function place_order_pay_later($_data, $customer, $website, $editor) {


    $customer->editor = $editor;


    $order_key = $_data['order_key'];

    //$order=get_object('Order',$order_key);
    include_once 'class.Order.php';
    $order = new Order($order_key);


    $order->update(
        array('Order Current Dispatch State'=>'Submitted by Customer'),'no_history'
    );



    $response = array(
        'state' => 200,
        'key'   => $order->id,

    );

   

    echo json_encode($response);

}


?>
