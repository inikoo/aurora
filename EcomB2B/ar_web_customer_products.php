<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 12:54:36 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_web_common.php';
require_once 'utils/get_addressing.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'category_products':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),


                     )
        );
        login($db, $data, $customer_key, $order_key);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}

function login($db, $data, $customer_key, $order_key) {


    $favourite = array();
    $ordered_products     = array();

    if ($customer_key) {
        $sql = sprintf(
            'SELECT `Customer Favourite Product Product ID`,`Customer Favourite Product Key` FROM `Customer Favourite Product Fact` WHERE `Customer Favourite Product Customer Key`=%d ', $customer_key
        );
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $favourite[$row['Customer Favourite Product Product ID']] = $row['Customer Favourite Product Key'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }
    }


    if ($order_key) {
        $sql = sprintf(
            'SELECT `Product ID`,`Order Quantity` FROM `Order Transaction Fact` WHERE `Order Key`=%d ', $order_key
        );
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $ordered_products[$row['Product ID']] = $row['Order Quantity'];
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }
    }

    echo json_encode(
        array(
            'state'     => 200,
            'favourite' => $favourite,
            'ordered_products'=>$ordered_products
        )
    );
    exit;


}


?>
