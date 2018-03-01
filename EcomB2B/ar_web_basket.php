<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 09:38:41 CEST, Trnava, Slavakia
 Copyright (c) 2016, Inikoo

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

    case 'update_item':
        $data = prepare_values(
            $_REQUEST, array(
                         'product_id'        => array('type' => 'key'),
                         'qty'               => array('type' => 'string'),
                         'order_key'         => array('type' => 'string'),
                         'webpage_key'       => array('type' => 'numeric'),
                         'page_section_type' => array('type' => 'string')
                     )
        );

        update_item($data, $customer, $website, $editor, $db);


        break;

    case 'update_favourite':
        $data = prepare_values(
            $_REQUEST, array(
                         'pid'           => array('type' => 'key'),
                         'customer_key'  => array('type' => 'key'),
                         'favourite_key' => array('type' => 'numeric'),

                     )
        );

        update_favourite($data, $customer, $website, $editor, $db);


        break;
    case 'get_charges_info':

        get_charges_info($db, $order);
        break;
    case 'special_instructions':
        $data = prepare_values(
            $_REQUEST, array(
                         'value' => array('type' => 'string'),

                     )
        );
        update_special_instructions($db, $data, $order, $editor);
        break;
    case 'invoice_address':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        invoice_address($db, $data, $order, $editor,$website);
        break;
    case 'delivery_address':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        delivery_address($db, $data, $order, $editor, $website);
        break;
}

function update_item($_data, $customer, $website, $editor, $db) {


    $customer->editor = $editor;


    $order_key = $_data['order_key'];
    if (!$order_key) {

        $order_key = $customer->get_order_in_process_key();
    }

    if (!$order_key) {

        $order = create_order($editor, $customer);
        $order->update(array('Order Website Key' => $website->id), 'no_history');


    } else {
        //$order=get_object('Order',$order_key);

        $order = get_object('Order', $order_key);
    }


    //$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);


    $product_pid = $_data['product_id'];
    $quantity    = $_data['qty'];


    if ($quantity == '') {
        $quantity = 0;
    }

    if (is_numeric($quantity) and $quantity >= 0) {
        $quantity = ceil($quantity);


        $dispatching_state = 'In Process';


        $payment_state = 'Waiting Payment';


        $product = get_object('Product', $product_pid);
        $data    = array(
            'date'                      => gmdate('Y-m-d H:i:s'),
            'item_historic_key'         => $product->get('Product Current Key'),
            'item_key'                  => $product->id,
            'Metadata'                  => '',
            'qty'                       => $quantity,
            'Current Dispatching State' => $dispatching_state,
            'Current Payment State'     => $payment_state
        );

        $discounted_products                             = $order->get_discounted_products();
        $order->skip_update_after_individual_transaction = false;
        //print_r($data);
        $transaction_data = $order->update_item($data);


        $discounts_data = array();


        $sql = sprintf(
            'SELECT `Order Transaction Amount`,OTF.`Product ID`,OTF.`Product Key`,`Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Order Currency Code`,OTF.`Order Transaction Fact Key`, `Deal Info` FROM `Order Transaction Fact` OTF LEFT JOIN  `Order Transaction Deal Bridge` B ON (OTF.`Order Transaction Fact Key`=B.`Order Transaction Fact Key`) WHERE OTF.`Order Key`=%s ',
            $order->id
        );

        if ($result = $db->query($sql)) {
            foreach ($result as $row) {


                $discounts_data[$row['Order Transaction Fact Key']] = array(
                    'deal_info' => $row['Deal Info'],
                    'item_net'  => money($row['Order Transaction Amount'], $row['Order Currency Code'])
                );


            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $basket_history = array(
            'otf_key'                 => $transaction_data['otf_key'],
            'Webpage Key'             => $_data['webpage_key'],
            'Product ID'              => $product->id,
            'Quantity Delta'          => $transaction_data['delta_qty'],
            'Quantity'                => $transaction_data['qty'],
            'Net Amount Delta'        => $transaction_data['delta_net_amount'],
            'Net Amount'              => $transaction_data['net_amount'],
            'Page Store Section Type' => $_data['page_section_type'],

        );
        $order->add_basket_history($basket_history);


        $new_discounted_products = $order->get_discounted_products();
        foreach ($new_discounted_products as $key => $value) {
            $discounted_products[$key] = $value;
        }

        /*
        $adata = array();

        if (count($discounted_products) > 0) {

            $product_keys = join(',', $discounted_products);
            $sql          = sprintf(
                "SELECT
			(SELECT group_concat(`Deal Info` SEPARATOR ', ') FROM `Order Transaction Deal Bridge` OTDB WHERE OTDB.`Order Key`=OTF.`Order Key` AND OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) AS `Deal Info`,
			P.`Product Name`,P.`Product Units Per Case`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` FROM `Order Transaction Fact` OTF   LEFT JOIN `Product Dimension` P ON (OTF.`Product ID`=P.`Product ID`) WHERE OTF.`Order Key`=%d AND OTF.`Product Key` IN (%s)",
                $order->id, $product_keys
            );


            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $deal_info = '';
                    if ($row['Deal Info'] != '') {
                        $deal_info = ' <span class="deal_info">'.$row['Deal Info'].'</span>';
                    }

                    $adata[$row['Product ID']] = array(
                        'pid'         => $row['Product ID'],
                        'description' => $row['Product Units Per Case'].'x '.$row['Product Name'].$deal_info,
                        'to_charge'   => money($row['Order Transaction Gross Amount'] - $row['Order Transaction Total Discount Amount'], $order->data['Order Currency'])
                    );
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


        }
*/

        $labels = $website->get('Localised Labels');

        if ($order->get('Shipping Net Amount') == 'TBC') {
            $shipping_amount = sprintf('<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>%s</small>', (!empty($labels['_we_will_contact_you']) ? $labels['_we_will_contact_you'] : _('We will contact you')));
        } else {
            $shipping_amount = $order->get('Shipping Net Amount');
        }

        $class_html = array(
            'order_items_gross'       => $order->get('Items Gross Amount'),
            'order_items_discount'    => $order->get('Items Discount Amount'),
            'order_items_net'         => $order->get('Items Net Amount'),
            'order_net'               => $order->get('Total Net Amount'),
            'order_tax'               => $order->get('Total Tax Amount'),
            'order_charges'           => $order->get('Charges Net Amount'),
            'order_credits'           => $order->get('Net Credited Amount'),
            'order_shipping'          => $shipping_amount,
            'order_total'             => $order->get('Total Amount'),
            'ordered_products_number' => $order->get('Number Items'),
        );


        $response = array(
            'state'               => 200,
            'quantity'            => $transaction_data['qty'],
            'product_pid'         => $product_pid,
            'description'         => $product->data['Product Units Per Case'].'x '.$product->data['Product Name'],
            'discount_percentage' => $transaction_data['discount_percentage'],
            'key'                 => $order->id,

            'metadata' => array('class_html' => $class_html),


            'to_charge'      => $transaction_data['to_charge'],
            'discounts_data' => $discounts_data,
            'discounts'      => ($order->data['Order Items Discount Amount'] != 0 ? true : false),
            'charges'        => ($order->data['Order Charges Net Amount'] != 0 ? true : false)
        );
    } else {
        $response = array('state' => 200);
    }


    echo json_encode($response);

}


function create_order($editor, $customer) {


    $order_data = array(
        'Order Current Dispatch State' => 'In Process',
        'editor'                       => $editor
    );


    $order = $customer->create_order($order_data);


    return $order;
}


function invoice_address($db, $data, $order, $editor,$website) {


    $address_data = array(
        'Address Line 1'               => '',
        'Address Line 2'               => '',
        'Address Sorting Code'         => '',
        'Address Postal Code'          => '',
        'Address Dependent Locality'   => '',
        'Address Locality'             => '',
        'Address Administrative Area'  => '',
        'Address Country 2 Alpha Code' => '',
    );


    foreach ($data['data'] as $key => $value) {

        if ($key == 'addressLine1') {
            $key = 'Address Line 1';
        } elseif ($key == 'addressLine2') {
            $key = 'Address Line 2';
        } elseif ($key == 'sortingCode') {
            $key = 'Address Sorting Code';
        } elseif ($key == 'postalCode') {
            $key = 'Address Postal Code';
        } elseif ($key == 'dependentLocality') {
            $key = 'Address Dependent Locality';
        } elseif ($key == 'locality') {
            $key = 'Address Locality';
        } elseif ($key == 'administrativeArea') {
            $key = 'Address Administrative Area';
        } elseif ($key == 'country') {
            $key = 'Address Country 2 Alpha Code';
        }

        $address_data[$key] = $value;

    }


    $order->editor = $editor;
    $order->update(array('Order Invoice Address' => json_encode($address_data)));


    $labels = $website->get('Localised Labels');

    if ($order->get('Shipping Net Amount') == 'TBC') {
        $shipping_amount = sprintf('<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>%s</small>', (!empty($labels['_we_will_contact_you']) ? $labels['_we_will_contact_you'] : _('We will contact you')));
    } else {
        $shipping_amount = $order->get('Shipping Net Amount');
    }


    $class_html = array(
        'order_items_gross'       => $order->get('Items Gross Amount'),
        'order_items_discount'    => $order->get('Items Discount Amount'),
        'order_items_net'         => $order->get('Items Net Amount'),
        'order_net'               => $order->get('Total Net Amount'),
        'order_tax'               => $order->get('Total Tax Amount'),
        'order_charges'           => $order->get('Charges Net Amount'),
        'order_credits'           => $order->get('Net Credited Amount'),
        'order_shipping'          => $shipping_amount,
        'order_total'             => $order->get('Total Amount'),
        'ordered_products_number' => $order->get('Number Items'),


        'formatted_invoice_address' => $order->get('Order Invoice Address Formatted'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => array(
            'class_html'     => $class_html,
            'for_collection' => $order->get('Order For Collection')
        ),

    );
    echo json_encode($response);


}

function delivery_address($db, $data, $order, $editor, $website) {


    $order->editor = $editor;


    if ($data['data']['order_for_collection']) {
        $order->update(array('Order For Collection' => 'Yes'));

    } else {
        $order->update(array('Order For Collection' => 'No'));

        $address_data = array(
            'Address Line 1'               => '',
            'Address Line 2'               => '',
            'Address Sorting Code'         => '',
            'Address Postal Code'          => '',
            'Address Dependent Locality'   => '',
            'Address Locality'             => '',
            'Address Administrative Area'  => '',
            'Address Country 2 Alpha Code' => '',
        );


        foreach ($data['data'] as $key => $value) {

            if ($key == 'addressLine1') {
                $key = 'Address Line 1';
            } elseif ($key == 'addressLine2') {
                $key = 'Address Line 2';
            } elseif ($key == 'sortingCode') {
                $key = 'Address Sorting Code';
            } elseif ($key == 'postalCode') {
                $key = 'Address Postal Code';
            } elseif ($key == 'dependentLocality') {
                $key = 'Address Dependent Locality';
            } elseif ($key == 'locality') {
                $key = 'Address Locality';
            } elseif ($key == 'administrativeArea') {
                $key = 'Address Administrative Area';
            } elseif ($key == 'country') {
                $key = 'Address Country 2 Alpha Code';
            }

            $address_data[$key] = $value;

        }

        $order->update(array('Order Delivery Address' => json_encode($address_data)));

    }


    $labels = $website->get('Localised Labels');

    if ($order->get('Shipping Net Amount') == 'TBC') {
        $shipping_amount = sprintf('<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>%s</small>', (!empty($labels['_we_will_contact_you']) ? $labels['_we_will_contact_you'] : _('We will contact you')));
    } else {
        $shipping_amount = $order->get('Shipping Net Amount');
    }

    $class_html = array(
        'order_items_gross'       => $order->get('Items Gross Amount'),
        'order_items_discount'    => $order->get('Items Discount Amount'),
        'order_items_net'         => $order->get('Items Net Amount'),
        'order_net'               => $order->get('Total Net Amount'),
        'order_tax'               => $order->get('Total Tax Amount'),
        'order_charges'           => $order->get('Charges Net Amount'),
        'order_credits'           => $order->get('Net Credited Amount'),
        'order_shipping'          => $shipping_amount,
        'order_total'             => $order->get('Total Amount'),
        'ordered_products_number' => $order->get('Number Items'),


        'formatted_delivery_address' => $order->get('Order Delivery Address Formatted'),


    );


    $response = array(
        'state'    => 200,
        'metadata' => array(
            'class_html'     => $class_html,
            'for_collection' => $order->get('Order For Collection')
        ),

    );


    echo json_encode($response);


}


function update_special_instructions($db, $data, $order, $editor) {


    $order->editor = $editor;

    $order->update(
        array('Order Customer Message' => $data['value']), 'no_history'

    );
    $response = array(
        'state' => 200,


    );
    echo json_encode($response);

}


function update_favourite($data, $customer, $website, $editor, $db) {


    $customer->editor = $editor;

    if ($data['favourite_key']) {

        $sql = sprintf('DELETE FROM `Customer Favourite Product Fact` WHERE `Customer Favourite Product Key`=%d ', $data['favourite_key']);


        $db->exec($sql);

        $favourite_key = 0;
        $pid           = $data['pid'];

    } else {

        $product = get_object('Product', $data['pid']);
        $sql     = sprintf(
            'INSERT INTO  `Customer Favourite Product Fact` (`Customer Favourite Product Customer Key`,`Customer Favourite Product Product ID`,`Customer Favourite Product Store Key`,`Customer Favourite Product Creation Date`) VALUES
		(%d,%d,%d,%s) ON DUPLICATE KEY UPDATE `Customer Favourite Product Store Key`=%d
		', $data['customer_key'], $product->id, $product->data['Product Store Key'],

            prepare_mysql(gmdate('Y-m-d H:i:s')), $product->data['Product Store Key']

        );

        // print $sql;
        $db->exec($sql);

        $favourite_key = $db->lastInsertId();
        $pid           = $product->id;

    }

    $response = array(
        'state'         => 200,
        'favourite_key' => $favourite_key,
        'pid'           => $pid
    );
    echo json_encode($response);


}

function get_charges_info($db, $order) {


    $response = array(
        'state' => 200,
        'title' => _('Charges'),
        'text'  => $order->get_charges_public_info()
    );
    echo json_encode($response);

}


?>
