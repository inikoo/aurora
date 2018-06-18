<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 12:54:36 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';


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
                         'webpage_key' => array(
                             'type'     => 'key',
                             'optional' => true
                         ),


                     )
        );
        category_products($db, $customer->id, $order);
        break;



    case 'total_basket':

        total_basket($order);
        break;
    case 'out_of_stock_reminders':

        out_of_stock_reminders($db, $customer->id, $order);


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


function total_basket($order) {

    $website = get_object('Website', $_SESSION['website_key']);
    $labels  = $website->get('Localised Labels');





    if(!$order->id){
        $total = 0;
        $label  = (isset($labels['_total']) ? $labels['_total'] : _('Total'));
        $items = 0;
    }else{
        if (!empty($website->settings['Info Bar Basket Amount Type']) and $website->settings['Info Bar Basket Amount Type'] == 'items_net') {
            $total = $order->get('Items Net Amount');
            $label  = (isset($labels['_items_net']) ? $labels['_items_net'] : _('Items Net'));
            $items = $order->get('Products');

        } else {
            $total = $order->get('Total');
            $label  = (isset($labels['_total']) ? $labels['_total'] : _('Total'));
            $items = $order->get('Products');
        }
    }




    echo json_encode(
        array(
            'state' => 200,
            'total' => $total,
            'items' => $items,
            'label' => $label
        )
    );

}

function category_products($db, $customer_key, $order) {


    $website = get_object('Website', $_SESSION['website_key']);
    $labels  = $website->get('Localised Labels');





    if(!$order->id){
        $total = 0;
        $label  = (isset($labels['_total']) ? $labels['_total'] : _('Total'));
        $items = 0;
    }else{
        if (!empty($website->settings['Info Bar Basket Amount Type']) and $website->settings['Info Bar Basket Amount Type'] == 'items_net') {
            $total = $order->get('Items Net Amount');
            $label  = (isset($labels['_items_net']) ? $labels['_items_net'] : _('Items Net'));
            $items = $order->get('Products');

        } else {
            $total = $order->get('Items Net Amount');
            $label  = (isset($labels['_total']) ? $labels['_total'] : _('Total'));
            $items = $order->get('Products');
        }
    }



    $favourite        = array();
    $out_of_stock_reminders        = array();
    $ordered_products = array();


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


    $sql = sprintf(
        'SELECT `Back in Stock Reminder Product ID`,`Back in Stock Reminder Key` FROM `Back in Stock Reminder Fact` WHERE `Back in Stock Reminder Customer Key`=%d ', $customer_key
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $out_of_stock_reminders[$row['Back in Stock Reminder Product ID']] = $row['Back in Stock Reminder Key'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $sql = sprintf(
        'SELECT `Product ID`,`Order Quantity` FROM `Order Transaction Fact` WHERE `Order Key`=%d ', $order->id
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


    echo json_encode(
        array(
            'state'            => 200,
            'favourite'        => $favourite,
            'out_of_stock_reminders'        => $out_of_stock_reminders,

            'ordered_products' => $ordered_products,
            'total' => $total,
            'items' => $items,
            'label' => $label
        )
    );
    exit;


}


?>
