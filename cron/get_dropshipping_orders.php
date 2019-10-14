<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2013 Inikoo


//include_once 'dropshipping_common_functions.php';

$dropshipping_location_key = 15221;

require_once 'common.php';
include 'class.Customer.php';
include 'class.PartLocation.php';
include 'class.TaxCategory.php';


require_once 'class.Country.php';
require_once 'utils/get_addressing.php';
include_once 'utils/data_entry_picking_aid.class.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Magento import)'
);
$store  = get_object('Store', 9);

$credits = array();

//$sql= "SELECT * FROM `drop`.`sales_flat_order` where entity_id=141387	";
$sql = sprintf("SELECT * FROM `drop`.`sales_flat_order` where updated_at>'%s' ", date('Y-m-d H:i:s', strtotime('-2 month')));
//$sql = "SELECT * FROM `drop`.`sales_flat_order` where increment_id='DS76541'";


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $shipping_net = 0;
        $store_code    = $store->data['Store Code'];
        $order_data_id = $row['entity_id'];

        $sql = sprintf(
            "select * from `Order Import Metadata` where `Metadata`=%s and `Import Date`>=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at'])

        );

        if ($resxx = $db->query($sql)) {
            if ($rowxx = $resxx->fetch()) {
                  continue;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }
        if (!in_array(
            $row['state'], array(
                             'canceled',
                             'closed',
                             'complete',
                             'processing'
                         )
        )) {
            continue;
        }


        $sql = sprintf(
            "select `Order Key` from `Order Dimension` where `Order Import ID`=%s ", prepare_mysql($row['entity_id'])

        );

        // print "$sql\n";

        if ($result_aaa = $db->query($sql)) {
            if ($row_aa = $result_aaa->fetch()) {

                if ($row['state'] == 'processing') {
                    $sql = sprintf(
                        "INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at']), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at'])
                    );

                    $db->exec($sql);
                    continue;
                }


                $order = get_object('Order', $row_aa['Order Key']);

                $editor        = array(
                    'Author Name'  => '',
                    'Author Alias' => '',
                    'Author Type'  => '',
                    'Author Key'   => '',
                    'User Key'     => 0,
                    'Date'         => gmdate('Y-m-d H:i:s'),
                    'Subject'      => 'System',
                    'Subject Key'  => 0,
                    'Author Name'  => 'Script (Magento import)'
                );
                $order->editor = $editor;
                if ($row['state'] == 'canceled') {
                    $order->cancel('');
                    $sql = sprintf(
                        "INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at']), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at'])
                    );

                    $db->exec($sql);

                    print 'Cancelled '.$row['increment_id'].' '.$row['updated_at']." ".$row['state']." \n";

                    continue;
                }

                if ($row['state'] == 'complete') {
                    if ($order->get('State Index') == 100) {

                        $sql = sprintf(
                            "INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at']), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at'])
                        );

                        $db->exec($sql);

                        continue;
                    } else {


                        if ($order->get('State Index') == 90 or $order->get('State Index') == 80) {


                            $msg = "Order ".$order->id." ".$order->get('Public ID')." need to be fixed manually\n";


                            $sql = sprintf(
                                "delete from `Order Import Metadata` where `Metadata`=%s  ", prepare_mysql($store_code.$order_data_id)
                            );

                            $db->exec($sql);

                            print $msg;


                            continue;

                        }
                        print 'Updating '.$row['increment_id'].' '.$row['updated_at']." ".$row['state']." \n";


                        if ($order->get('State Index') == 40) {

                            $dn_to_delete = get_object('delivery_note', $order->get('Delivery Note Key'));
                            $dn_to_delete->delete();


                        }


                        $header_data = read_header($row);
                        list($shipping_net, $data_dn_transactions) = get_ds_items($row, $header_data);


                        list($name, $address1, $address2, $town, $postcode, $country_div, $country) = get_address($row['shipping_address_id']);
                        $country                 = new Country('find', $country);
                        $delivery_address_fields = address_fields(
                            array(
                                'country_code'        => $country->data['Country Code'],
                                'country_2alpha_code' => $country->data['Country 2 Alpha Code'],

                                'country_d1'  => $country_div,
                                'country_d2'  => '',
                                'town'        => $town,
                                'town_d1'     => '',
                                'town_d2'     => '',
                                'postal_code' => $postcode,
                                'street'      => '',
                                'internal'    => $address2,
                                'building'    => $address1,

                            ), '', $name, 'GB'
                        );

                        $order->update_field_switcher('Order Delivery Address', json_encode($delivery_address_fields), 'no_history');
                        list($date_order, $date_invoiced, $date_refunded, $history) = get_history($row['entity_id']);

                        // print_r($delivery_address_fields);

                        //print_r($data_dn_transactions);

                        $editor        = array(
                            'Author Name'  => '',
                            'Author Alias' => '',
                            'Author Type'  => '',
                            'Author Key'   => '',
                            'User Key'     => 0,
                            'Date'         => $date_invoiced,
                            'Subject'      => 'System',
                            'Subject Key'  => 0,
                            'Author Name'  => 'Script (Magento import)'
                        );
                        $order->editor = $editor;


                        foreach ($order->get_items() as $old_items) {
                            $dispatching_state = 'In Process';


                            $payment_state = 'Waiting Payment';


                            $item_transaction = array(
                                'item_historic_key'         => $old_items['product_historic_key'],
                                'item_key'                  => $old_items['product_id'],
                                'Metadata'                  => '',
                                'qty'                       => 0,
                                'Current Dispatching State' => $dispatching_state,
                                'Current Payment State'     => $payment_state
                            );


                            $transaction = $order->update_item($item_transaction);


                        }


                        $order->update_field_switcher('Order Delivery Address', json_encode($delivery_address_fields), 'no_history');


                        $old_payments = array();
                        foreach ($order->get_payments('objects') as $_payment) {
                            $old_payments[$_payment->get('Payment Transaction ID')] = $_payment->get('Payment Transaction ID');
                        }

                        foreach (get_payments($row['entity_id']) as $payment) {


                            if (!in_array($payment['last_trans_id'], $old_payments)) {

                                // print_r($payment);
                                $payment_account = get_object('Payment_Account', 1);
                                $payment_data    = array(
                                    'Payment Store Key'                   => $order->get('Store Key'),
                                    'Payment Customer Key'                => $order->get('Customer Key'),
                                    'Payment Transaction Amount'          => $payment['amount_paid'],
                                    'Payment Currency Code'               => $order->get('Currency Code'),
                                    'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
                                    'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
                                    'Payment Sender Email'                => $order->get('Email'),
                                    'Payment Sender Card Type'            => '',
                                    'Payment Created Date'                => $date_order,
                                    'Payment Order Key'                   => $order->id,
                                    'Payment Completed Date'              => $date_order,
                                    'Payment Last Updated Date'           => $date_order,
                                    'Payment Transaction Status'          => 'Completed',
                                    'Payment Transaction ID'              => $payment['last_trans_id'],
                                    'Payment Method'                      => 'Paypal',
                                    'Payment Location'                    => 'Order',
                                    'Payment Metadata'                    => '',
                                    'Payment Submit Type'                 => 'EPS',
                                    'Payment Currency Exchange Rate'      => 1,
                                    'Payment Type'                        => 'Payment'


                                );

                                $_payment = $payment_account->create_payment($payment_data);


                                $order->add_payment($_payment);
                                $order->update_totals();
                            }


                        }


                        foreach ($data_dn_transactions as $data_dn_transaction) {
                            $dispatching_state = 'Submitted by Customer';


                            $payment_state = 'Paid';


                            $product          = get_object('Product', $data_dn_transaction['Product Key']);
                            $item_transaction = array(
                                'item_historic_key'         => $product->get('Product Current Key'),
                                'item_key'                  => $product->id,
                                'Metadata'                  => '',
                                'qty'                       => $data_dn_transaction['Order Quantity'],
                                'Current Dispatching State' => $dispatching_state,
                                'Current Payment State'     => $payment_state
                            );

                            //print_r($item_transaction);

                            $transaction = $order->update_item($item_transaction);
                            // print_r($transaction);
                            $sql = sprintf(
                                'update `Order Transaction Fact` 
                set `Order Date`=%s, `Order Last Updated Date`=%s
                where  `Order Transaction Fact Key`=%d', prepare_mysql($date_order), prepare_mysql($date_order), $transaction['otf_key']
                            );

                            $db->exec($sql);

                        }

                        $order->update_totals();

                        $order->update_shipping_amount($shipping_net);


                        $order = dispatch_order($date_invoiced, $order);


                        $sql = sprintf(
                            "INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at']), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at'])
                        );

                        $db->exec($sql);
                        continue;

                    }

                }

                if ($row['state'] == 'closed'    ) {


                    if(count($order->get_invoices('keys','refunds_only'))>0){
                        continue;
                    }






                    list($date_order, $date_invoiced, $date_refunded, $history) = get_history($row['entity_id']);

                    $refund_transactions = array();

                    $sql = sprintf('select `Order Transaction Fact Key`,`Order Transaction Amount` from `Order Transaction Fact` where `Order Key`=%s  ', $order->id);
                    if ($resultxx = $db->query($sql)) {
                        foreach ($resultxx as $row_xx) {

                            $refund_transactions[] = array(
                                'type'   => 'otf',
                                'id'     => $row_xx['Order Transaction Fact Key'],
                                'amount' => $row_xx['Order Transaction Amount'],

                            );

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                    $sql = sprintf('select `Order No Product Transaction Fact Key`,`Transaction Net Amount` from `Order No Product Transaction Fact` where `Order Key`=%s  ', $order->id);
                    if ($resultxx = $db->query($sql)) {
                        foreach ($resultxx as $row_xx) {

                            $refund_transactions[] = array(
                                'type'   => 'onptf',
                                'id'     => $row_xx['Order No Product Transaction Fact Key'],
                                'amount' => $row_xx['Transaction Net Amount'],

                            );

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                    $_editor = array(
                        'Author Name'  => '',
                        'Author Alias' => '',
                        'Author Type'  => '',
                        'Author Key'   => '',
                        'User Key'     => 0,
                        'Date'         => $date_refunded,
                        'Subject'      => 'System',
                        'Subject Key'  => 0,
                        'Author Name'  => 'Script (Magento import)'
                    );

                    $order->editor = $_editor;
                    $refund        = $order->create_refund($date_refunded, $refund_transactions);


                    foreach ($order->get_payments('objects', 'Completed') as $_payment) {
                        $payment_account         = get_object('Payment_Account', 1);
                        $payment_account->editor = $_editor;

                        $payment_data = array(
                            'Payment Store Key'                   => $order->get('Store Key'),
                            'Payment Customer Key'                => $order->get('Customer Key'),
                            'Payment Transaction Amount'          => -$_payment->get('Payment Transaction Amount'),
                            'Payment Currency Code'               => $order->get('Currency Code'),
                            'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
                            'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
                            'Payment Sender Email'                => $order->get('Email'),
                            'Payment Sender Card Type'            => '',
                            'Payment Created Date'                => $date_refunded,
                            'Payment Order Key'                   => $order->id,
                            'Payment Completed Date'              => $date_refunded,
                            'Payment Last Updated Date'           => $date_refunded,
                            'Payment Transaction Status'          => 'Completed',
                            'Payment Transaction ID'              => '',
                            'Payment Method'                      => 'Paypal',
                            'Payment Location'                    => 'Order',
                            'Payment Metadata'                    => '',
                            'Payment Submit Type'                 => 'EPS',
                            'Payment Currency Exchange Rate'      => 1,
                            'Payment Type'                        => 'Payment'


                        );

                        $payment = $payment_account->create_payment($payment_data);


                        $order->add_payment($payment);
                        $order->update_totals();

                        $refund->add_payment($payment);
                    }


                    $sql = sprintf(
                        "INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at']), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at'])
                    );

                    $db->exec($sql);
                    continue;

                }


            } else {

            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        print 'New '.$row['increment_id'].' '.$row['updated_at']."\n";


        $_customer_key = 0;
        $sql           = sprintf("select `Customer Key` from `Customer Dimension` where `Customer Old ID`=%s and `Customer Store Key`=%d", prepare_mysql($row['customer_id']), $store->id);

        if ($result__ = $db->query($sql)) {
            if ($row__ = $result__->fetch()) {
                $_customer_key = $row__['Customer Key'];
            }
        } else {
            print_r($error_info = $store->db->errorInfo());
            exit;
        }

        $customer = get_object('Customer', $_customer_key);


        if ($customer->id) {


            $header_data         = read_header($row);
            $tax_category_object = get_tax_code($store->data['Store Code'], $header_data);


            $header_data['pickedby'] = 'callum';
            $header_data['packedby'] = 'callum';

            $customer_service_rep_data = array('id' => 0);
            $customer_key              = $customer->id;
            $filename                  = '';

            $date_order = $row['created_at'];


            $charges_net = 0;


            list($shipping_net, $data_dn_transactions) = get_ds_items($row, $header_data);


            list($name, $address1, $address2, $town, $postcode, $country_div, $country) = get_address($row['shipping_address_id']);

            $country = new Country('find', $country);


            $delivery_address_fields = address_fields(
                array(
                    'country_code'        => $country->data['Country Code'],
                    'country_2alpha_code' => $country->data['Country 2 Alpha Code'],

                    'country_d1'  => $country_div,
                    'country_d2'  => '',
                    'town'        => utf8_encode($town),
                    'town_d1'     => '',
                    'town_d2'     => '',
                    'postal_code' => $postcode,
                    'street'      => '',
                    'internal'    => utf8_encode($address2),
                    'building'    => utf8_encode($address1),

                ), '', $name, 'GB'
            );


            $data                                  = array();
            $editor['Date']                        = $row['created_at'];
            $data['editor']                        = $editor;
            $data['order_date']                    = $row['created_at'];
            $data['order id']                      = $row['increment_id'];
            $data['order customer message']        = $row['customer_note'];
            $data['order original data source']    = 'Magento';
            $data['Order Main Source Type']        = 'Internet';
            $data['Delivery Note Dispatch Method'] = 'Shipped';
            $data['staff sale']                    = 'no';
            $data['staff sale key']                = 0;
            $data['Order Customer Key']            = $customer->id;
            $data['Order Type']                    = 'Order';
            list($date_order, $date_invoiced, $date_refunded, $history) = get_history($row['entity_id']);
            $_editor          = array(
                'Author Name'  => '',
                'Author Alias' => '',
                'Author Type'  => '',
                'Author Key'   => '',
                'User Key'     => 0,
                'Date'         => $date_order,
                'Subject'      => 'System',
                'Subject Key'  => 0,
                'Author Name'  => 'Script (Magento import)'
            );
            $customer->editor = $_editor;
            $order            = $customer->create_order(json_encode(array('date' => $date_order)));

            $order->fast_update(
                array(
                    'Order Import ID' => $row['entity_id'],

                )
            );


            //   exit;
            //print_r($delivery_address_fields);
            //         json_encode($delivery_address_fields);
            //        echo json_last_error();
            $order->update_field_switcher('Order Delivery Address', json_encode($delivery_address_fields), 'no_history');

            $order->fast_update(
                array(
                    'Order Public ID'   => $data['order id'],
                    'Order File As'     => $data['order id'],
                    'Order Sticky Note' => $data['order customer message'],
                )
            );
            foreach ($data_dn_transactions as $data_dn_transaction) {
                $dispatching_state = 'In Process';


                $payment_state = 'Waiting Payment';


                $product          = get_object('Product', $data_dn_transaction['Product Key']);
                $item_transaction = array(
                    'item_historic_key'         => $product->get('Product Current Key'),
                    'item_key'                  => $product->id,
                    'Metadata'                  => '',
                    'qty'                       => $data_dn_transaction['Order Quantity'],
                    'Current Dispatching State' => $dispatching_state,
                    'Current Payment State'     => $payment_state
                );
                $transaction      = $order->update_item($item_transaction);

                $sql = sprintf(
                    'update `Order Transaction Fact` 
                set `Order Date`=%s, `Order Last Updated Date`=%s
                where  `Order Transaction Fact Key`=%d', prepare_mysql($date_order), prepare_mysql($date_order), $transaction['otf_key']
                );

                $db->exec($sql);

            }
            foreach (get_payments($row['entity_id']) as $payment) {
                $payment_account = get_object('Payment_Account', 1);
                $payment_data    = array(
                    'Payment Store Key'                   => $order->get('Store Key'),
                    'Payment Customer Key'                => $order->get('Customer Key'),
                    'Payment Transaction Amount'          => $payment['amount_paid'],
                    'Payment Currency Code'               => $order->get('Currency Code'),
                    'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
                    'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
                    'Payment Sender Email'                => $order->get('Email'),
                    'Payment Sender Card Type'            => '',
                    'Payment Created Date'                => $date_order,
                    'Payment Order Key'                   => $order->id,
                    'Payment Completed Date'              => $date_order,
                    'Payment Last Updated Date'           => $date_order,
                    'Payment Transaction Status'          => 'Completed',
                    'Payment Transaction ID'              => $payment['last_trans_id'],
                    'Payment Method'                      => 'Paypal',
                    'Payment Location'                    => 'Order',
                    'Payment Metadata'                    => '',
                    'Payment Submit Type'                 => 'EPS',
                    'Payment Currency Exchange Rate'      => 1,
                    'Payment Type'                        => 'Payment'


                );

                $_payment = $payment_account->create_payment($payment_data);


                $order->add_payment($_payment);
                $order->update_totals();

            }
            $order->update_shipping_amount($shipping_net);
            $order->update_state('InProcess', json_encode(array('date' => $date_order)));


            if ($row['state'] == 'complete' or $row['state'] == 'closed') {

                $order = dispatch_order($date_invoiced, $order);


                if ($row['state'] == 'closed') {


                    $refund_transactions = array();

                    $sql = sprintf('select `Order Transaction Fact Key`,`Order Transaction Amount` from `Order Transaction Fact` where `Order Key`=%s  ', $order->id);
                    if ($resultxx = $db->query($sql)) {
                        foreach ($resultxx as $row_xx) {

                            $refund_transactions[] = array(
                                'type'   => 'otf',
                                'id'     => $row_xx['Order Transaction Fact Key'],
                                'amount' => $row_xx['Order Transaction Amount'],

                            );

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                    $sql = sprintf('select `Order No Product Transaction Fact Key`,`Transaction Net Amount` from `Order No Product Transaction Fact` where `Order Key`=%s  ', $order->id);
                    if ($resultxx = $db->query($sql)) {
                        foreach ($resultxx as $row_xx) {

                            $refund_transactions[] = array(
                                'type'   => 'onptf',
                                'id'     => $row_xx['Order No Product Transaction Fact Key'],
                                'amount' => $row_xx['Transaction Net Amount'],

                            );

                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                    $_editor = array(
                        'Author Name'  => '',
                        'Author Alias' => '',
                        'Author Type'  => '',
                        'Author Key'   => '',
                        'User Key'     => 0,
                        'Date'         => $date_refunded,
                        'Subject'      => 'System',
                        'Subject Key'  => 0,
                        'Author Name'  => 'Script (Magento import)'
                    );

                    $order->editor = $_editor;
                    $refund        = $order->create_refund($date_refunded, $refund_transactions);


                    $payment_account         = get_object('Payment_Account', 1);
                    $payment_account->editor = $_editor;

                    $payment_data = array(
                        'Payment Store Key'                   => $order->get('Store Key'),
                        'Payment Customer Key'                => $order->get('Customer Key'),
                        'Payment Transaction Amount'          => -$payment['amount_paid'],
                        'Payment Currency Code'               => $order->get('Currency Code'),
                        'Payment Sender'                      => $order->get('Order Invoice Address Recipient'),
                        'Payment Sender Country 2 Alpha Code' => $order->get('Order Invoice Address Country 2 Alpha Code'),
                        'Payment Sender Email'                => $order->get('Email'),
                        'Payment Sender Card Type'            => '',
                        'Payment Created Date'                => $date_refunded,
                        'Payment Order Key'                   => $order->id,
                        'Payment Completed Date'              => $date_refunded,
                        'Payment Last Updated Date'           => $date_refunded,
                        'Payment Transaction Status'          => 'Completed',
                        'Payment Transaction ID'              => '',
                        'Payment Method'                      => 'Paypal',
                        'Payment Location'                    => 'Order',
                        'Payment Metadata'                    => '',
                        'Payment Submit Type'                 => 'EPS',
                        'Payment Currency Exchange Rate'      => 1,
                        'Payment Type'                        => 'Payment'


                    );

                    $payment = $payment_account->create_payment($payment_data);


                    $order->add_payment($payment);
                    $order->update_totals();

                    $refund->add_payment($payment);

                }


            } elseif ($row['state'] == 'canceled') {
                $order->cancel('', $date_order);

            }


            $sql = sprintf(
                "INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at']), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at'])
            );

            $db->exec($sql);


        } else {


            if ((gmdate('U') - strtotime($row['updated_at'])) > 3600 * 24 * 365) {


                $sql = sprintf(
                    "INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at']), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at'])
                );

                $db->exec($sql);

                // print $sql;
                // exit;

            } else {
                print $row['increment_id'].' '.$row['customer_id']." customer not found\n";

            }


        }

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


function get_ds_items($row, $header_data) {
    global $db, $store;


    $data_dn_transactions = array();

    $shipping_net = $header_data['shipping'];

    $sql = sprintf(
        "select * from `drop`.`sales_flat_order_item` WHERE `order_id`=%d ", $row['entity_id']
    );

    if ($res2 = $db->query($sql)) {
        foreach ($res2 as $row2) {

            if (in_array(
                $row2['sku'], array(
                                'Freight-01',
                                'Freight-02',
                                'SUSA',
                                'SMalta',
                                'SF',
                                'NWS'
                            )
            )) {
                $amount = $row2['qty_ordered'] * $row2['original_price'];

                $shipping_net += $amount;
                continue;
            }


            $w = $row2['weight'];

            //	print 'ccaca';
            //
            $sql = sprintf(
                "select `Product ID` from `Product Dimension` where  `Product Store Key`=%d and  `Product Code`=%s    ORDER BY FIELD(`Product Status`, 'Active','Discontinuing','Suspended','Discontinued', 'InProcess') ,`Product Number of Parts` desc  ", $store->id,
                prepare_mysql($row2['sku'])
            );

            if ($_result = $db->query($sql)) {
                if ($rowxx = $_result->fetch()) {
                    $product = get_object('Product', $rowxx['Product ID']);

                } else {

                    print "$sql\n";

                    print 'product not found: '.$row2['sku']."\n";
                    exit();
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


            $parts = $product->get_parts();


            if (count($parts) == 0) {
                //product with no parts

                print 'Products with no parts '.$product->data['Product Code']."\n";
                continue;
            }

            $qty         = $row2['qty_ordered'];
            $price       = $row2['original_price'];
            $transaction = array(
                'Product Key'           => $product->id,
                'Estimated Weight'      => $w * $qty,
                'qty'                   => $qty,
                'gross_amount'          => $qty * $price,
                'discount_amount'       => $qty * $row2['price'],
                'units_per_case'        => 1,
                'code'                  => $product->data['Product Code'],
                'description'           => $row2['name'],
                'price'                 => $price,
                'order'                 => $qty,
                'reorder'               => 0,
                'bonus'                 => 0,
                'credit'                => 0,
                'rrp'                   => '',
                'discount'              => 0,
                'units'                 => 1,
                'supplier_code'         => '',
                'supplier_product_code' => '',
                'supplier_product_cost' => '',
                'w'                     => $w,
                'name'                  => $row2['name'],
                'fob'                   => '',
                'original_price'        => $price


            );


            $used_parts_sku = false;


            $data_dn_transactions = create_dn_invoice_transactions($data_dn_transactions, $transaction, $product, $used_parts_sku);


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    return array(
        $shipping_net,
        $data_dn_transactions
    );

}

function dispatch_order($date_invoiced, $order) {

    global $dropshipping_location_key, $db, $store, $account;


    $_editor       = array(
        'Author Name'  => '',
        'Author Alias' => '',
        'Author Type'  => '',
        'Author Key'   => '',
        'User Key'     => 0,
        'Date'         => $date_invoiced,
        'Subject'      => 'System',
        'Subject Key'  => 0,
        'Author Name'  => 'Script (Magento import)'
    );
    $order->editor = $_editor;

    // add dropshipping location to parts if missing
    $items = $order->get_items();
    foreach ($items as $item) {
        $product = get_object('Product', $item['product_id']);
        foreach ($product->get_parts('objects') as $part) {

            if (count($part->get_locations('keys', '', true)) == 0) {


                $_editor = array(
                    'Author Name'  => '',
                    'Author Alias' => '',
                    'Author Type'  => '',
                    'Author Key'   => '',
                    'User Key'     => 0,
                    'Date'         => $date_invoiced,
                    'Subject'      => 'System',
                    'Subject Key'  => 0,
                    'Author Name'  => 'Script (Magento import)'
                );

                $part_location_data = array(
                    'Location Key' => $dropshipping_location_key,
                    'Part SKU'     => $part->id,
                    'editor'       => $_editor
                );


                $part_location         = new PartLocation('find', $part_location_data, 'create');
                $part_location->editor = $_editor;

                $part_location->audit(0, 'needed for picking dropshipping order', $_editor['Date']);


            }

        }


    };


    $order->update_state('InWarehouse', json_encode(array('date' => $date_invoiced)));


    $items = array();
    $sql   = sprintf(
        'select PD.`Part SKU`,`Required`+`Given` as required,L.`Location Key`,`Inventory Transaction Key`  from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  LEFT JOIN  `Part Location Dimension` PL ON  (ITF.`Location Key`=PL.`Location Key` and ITF.`Part SKU`=PL.`Part SKU`) left join `Location Dimension` L on (L.`Location Key`=ITF.`Location Key`)
    left join `Order Transaction Fact` on (`Order Transaction Fact Key`= `Map To Order Transaction Fact Key`) where ITF.`Delivery Note Key`=%d ', $order->get('Order Delivery Note Key')
    );


    // print "$sql\n";


    foreach ($db->query($sql) as $_data) {
        $items[$_data['Part SKU']] = array(
            array(
                "location_key" => $_data['Location Key'],
                "part_sku"     => $_data['Part SKU'],
                "itf_key"      => $_data['Inventory Transaction Key'],
                "qty"          => $_data['required']
            )
        );
    }


    //print_r($items);

    //exit;

    $_data = array(
        'delivery_note_key' => $order->get('Order Delivery Note Key'),
        'order_key'         => $order->id,
        'level'             => 30,
        'items'             => $items,
        'fields'            => array(
            "Delivery Note Assigned Picker Key" => $store->settings('data_entry_picking_aid_default_picker'),
            "Delivery Note Assigned Packer Key" => $store->settings('data_entry_picking_aid_default_packer'),

            "Delivery Note Weight"           => "",
            "Delivery Note Shipper Key"      => $store->settings('data_entry_picking_aid_default_shipper'),
            "Delivery Note Shipper Tracking" => "",
            "Delivery Note Number Parcels"   => "1"
        )

    );

    //print_r($_data);

    $_editor = array(
        'Author Name'  => '',
        'Author Alias' => '',
        'Author Type'  => '',
        'Author Key'   => '',
        'User Key'     => 0,
        'Date'         => $date_invoiced,
        'Subject'      => 'System',
        'Subject Key'  => 0,
        'Author Name'  => 'Script (Magento import)'
    );

    $data_entry_picking_aid = new data_entry_picking_aid($_data, $_editor, $db, $account);


    $validation = $data_entry_picking_aid->parse_input_data();
    if (!$validation['valid']) {


        echo json_encode($validation['response']);

    }


    $data_entry_picking_aid->update_delivery_note();


    $data_entry_picking_aid->process_transactions(json_encode(array('date' => $date_invoiced)));

    $data_entry_picking_aid->finish_packing(json_encode(array('date' => $date_invoiced)));

    return $order;


}


function read_header($data) {

    $header_data = get_empty_header();

    $header_data['date_order']  = $data['created_at'];
    $header_data['weight']      = $data['weight'];
    $header_data['total_topay'] = $data['grand_total'];
    $header_data['tax1']        = $data['tax_amount'];
    $header_data['total_net']   = $data['subtotal'] + $data['shipping_amount'];
    $header_data['shipping']    = $data['shipping_amount'];
    $header_data['notes']       = $data['customer_note'];


    //print_r($header_data);

    return $header_data;


}


function get_tax_code($type, $header_data) {


    switch ($type) {
        case 'E':
            $tax_cat_data = ci_get_tax_code($header_data);
            break;
        default:
            $tax_cat_data = uk_get_tax_code($header_data);
            break;
    }


    $tax_category = new TaxCategory('find', $tax_cat_data, 'create');


    return $tax_category;
}


function uk_get_tax_code($header_data) {

    global $db;

    $tax_rates = array();
    $tax_names = array();
    $sql       = sprintf("select * from `Tax Category Dimension` ");

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $tax_rates[$row['Tax Category Code']] = $row['Tax Category Rate'];
            $tax_names[$row['Tax Category Code']] = $row['Tax Category Name'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    $tax_code        = 'UNK';
    $tax_description = 'No Tax';
    $tax_rate        = 0;
    if ($header_data['total_net'] == 0) {
        $tax_code        = 'EX';
        $tax_description = '';
    } elseif ($header_data['total_net'] != 0 and $header_data['tax1'] + $header_data['tax2'] == 0) {

        $tax_code        = 'EX';
        $tax_description = '';
    } else {
        //  print "calcl tax coed";

        $tax_rate = ($header_data['tax1'] + $header_data['tax2']) / $header_data['total_net'];
        foreach ($tax_rates as $_tax_code => $_tax_rate) {
            // print "$_tax_code => $_tax_rate $tax_rate\n ";
            $upper = 1.02 * $_tax_rate;
            $lower = 0.98 * $_tax_rate;
            if ($tax_rate >= $lower and $tax_rate <= $upper) {
                $tax_code        = $_tax_code;
                $tax_description = $tax_names[$tax_code];
                $tax_rate        = $tax_rates[$tax_code];
                break;
            }
        }
    }

    $data = array(
        'Tax Category Code' => $tax_code,
        'Tax Category Name' => $tax_description,
        'Tax Category Rate' => $tax_rate
    );


    return $data;
}


function get_empty_header() {
    $header_data = array(
        'stipo'                    => '',
        'ltipo'                    => '',
        'pickedby'                 => '',
        'parcels'                  => '',
        'packedby'                 => '',
        'weight'                   => '',
        'trade_name'               => '',
        'takenby'                  => '',
        'customer_num'             => '',
        'order_num'                => '',
        'date_order'               => '',
        'date_inv'                 => '',
        'pay_method'               => '',
        'address1'                 => '',
        'history'                  => '',
        'address2'                 => '',
        'notes'                    => '',
        'total_net'                => '',
        'gold'                     => '',
        'address3'                 => '',
        'charges'                  => '',
        'tax1'                     => 0,
        'city'                     => '',
        'total_topay'              => '',
        'tax2'                     => 0,
        'postcode'                 => '',
        'notes2'                   => '',
        'shipping'                 => '',
        'customer_contact'         => '',
        'phone'                    => '',
        'total_order'              => '',
        'total_reorder'            => '',
        'total_bonus'              => '',
        'total_items_charge_value' => '',
        'total_rrp'                => '',
        'feedback'                 => '',
        'source_tipo'              => '',
        'extra_id1'                => '',
        'extra_id2'                => '',
        'dn_country_code'          => '',
        'collection'               => 'No'
    );

    $header_data['Order Main Source Type']        = 'Unknown';
    $header_data['Delivery Note Dispatch Method'] = 'Unknown';
    $header_data['staff sale key']                = 0;
    $header_data['collection']      = 'No';
    $header_data['shipper_code']    = '';
    $header_data['staff sale']      = 'No';
    $header_data['showroom']        = 'No';
    $header_data['staff sale name'] = '';


    return $header_data;
}


function create_dn_invoice_transactions($data_dn_transactions, $transaction, $product, $used_parts_sku) {
    // global $date_order, $products_data, $data_invoice_transactions, $estimated_w;


    if ($transaction['order'] > 0) {


        if ($transaction['order'] < $transaction['reorder']) {
            $transaction['reorder'] = $transaction['order'];
        }





        $products_data[] = array(
            'Product Key'      => $product->id,
            'Estimated Weight' => $product->data['Product Package Weight'] * $transaction['order'],
            'qty'              => $transaction['order'],
            'gross_amount'     => $transaction['order'] * $transaction['price'],
            'discount_amount'  => $transaction['order'] *
                $transaction['price'] *
                $transaction['discount'],
            'units_per_case'   => $product->data['Product Units Per Case']
        );

        //print_r($transaction);

        $net_amount   = round(($transaction['order'] - $transaction['reorder']) * $transaction['price'] * (1 - $transaction['discount']), 2);
        $gross_amount = round(($transaction['order'] - $transaction['reorder']) * $transaction['price'], 2);


        $data_dn_transactions[] = array(
            'otf_key'                            => '',
            'Code'                               => $product->get('Product Code'),
            'Product Key'                        => $product->id,
            'Estimated Weight'                   => $product->data['Product Package Weight'] * ($transaction['order'] - $transaction['reorder']),
            'Product ID'                         => $product->data['Product ID'],
            'Delivery Note Quantity'             => $transaction['order'] - $transaction['reorder'],
            'No Shipped Due Out of Stock'        => $transaction['reorder'],
            'Order Quantity'                     => $transaction['order'],

            'amount in'                          => (($transaction['order'] - $transaction['reorder']) * $transaction['price']) * (1 - $transaction['discount']),
            'given'                              => 0,
            'required'                           => $transaction['order'],
            'discount_amount'                    => $transaction['order'] * $transaction['price'] * $transaction['discount'],

            'pick_method'      => 'historic',
            'pick_method_data' => array(
                'parts_sku' => $used_parts_sku
            )
        );


    }
    if ($transaction['bonus'] > 0) {



        $data_dn_transactions[] = array(
            'otf_key'                            => '',
            'Code'                               => $product->code,
            'Product Key'                        => $product->id,
            'Product ID'                         => $product->data['Product ID'],
            'Delivery Note Quantity'             => $transaction['bonus'],
            'Order Quantity'                     => 0,
            'No Shipped Due Out of Stock'        => 0,
            'Estimated Weight'                   => $product->data['Product Package Weight'] * ($transaction['bonus']),
            'amount in'                          => 0,
            'given'                              => $transaction['bonus'],
            'discount_amount'                    => 0,
            'required'                           => 0,
            'pick_method'                        => 'historic',
            'pick_method_data'                   => array(
                'parts_sku' => $used_parts_sku
            )

        );


    }


    return $data_dn_transactions;

    //print_r($data_dn_transactions);

}

function address_fields($address_data, $recipient, $organization, $default_country) {


    //print_r($address_data);

    $country_2a = (($address_data['country_2alpha_code'] == 'XX' or $address_data['country_2alpha_code'] == '') ? $default_country : $address_data['country_2alpha_code']);

    $country_divs = preg_replace('/\, $|^\, /', '', $address_data['country_d1'].', '.$address_data['country_d2']);
    $town_divs    = preg_replace('/\, $|^\, /', '', $address_data['town_d1'].', '.$address_data['town_d2']);

    $address_format = get_address_format($country_2a);


    $_tmp = preg_replace('/,/', '', $address_format->getFormat());

    $used_fields = preg_split('/\s+/', preg_replace('/%/', '', $_tmp));


    $lines = array(
        1 => preg_replace('/\, $|^\, /', '', $address_data['internal'].', '.$address_data['building']),
        2 => $address_data['street']
    );

    $address_fields = array(
        'Address Recipient'            => $recipient,
        'Address Organization'         => $organization,
        'Address Line 1'               => $lines[1],
        'Address Line 2'               => $lines[2],
        'Address Sorting Code'         => '',
        'Address Postal Code'          => $address_data['postal_code'],
        'Address Dependent Locality'   => $town_divs,
        'Address Locality'             => $address_data['town'],
        'Address Administrative Area'  => $country_divs,
        'Address Country 2 Alpha Code' => $country_2a

    );

    //if (!in_array('recipient', $used_fields) or !in_array('organization', $used_fields) or !in_array('addressLine1', $used_fields)) {
    ////    print_r($used_fields);
    //    print_r($address->data);
    //    exit('no recipient or organization');
    // }

    if (!in_array('addressLine2', $used_fields)) {

        if ($address_fields['Address Line 2'] != '') {
            $address_fields['Address Line 1'] .= ', '.$address_fields['Address Line 2'];
        }
        $address_fields['Address Line 2'] = '';
    }

    if (!in_array('dependentLocality', $used_fields)) {

        if ($address_fields['Address Line 2'] == '') {
            $address_fields['Address Line 2'] = $address_fields['Address Dependent Locality'];
        } else {
            $address_fields['Address Line 2'] .= ', '.$address_fields['Address Dependent Locality'];
        }

        $address_fields['Address Dependent Locality'] = '';
    }

    if (!in_array('administrativeArea', $used_fields) and $country_divs != '') {
        $address_fields['Address Administrative Area'] = '';
        //print_r($address->data);
        //print_r($address_fields);

        //print $address->display();


        //exit;

        //print_r($used_fields);
        //print_r($address->data);
        //exit('administrativeArea problem');

    }

    if (!in_array('postalCode', $used_fields) and $address_data['postal_code'] != '') {

        if (in_array('sortingCode', $used_fields)) {
            $address_fields['Address Sorting Code'] = $address_fields['Address Postal Code'];
            $address_fields['Address Postal Code']  = '';

        } else {
            if (in_array('addressLine2', $used_fields)) {
                $address_fields['Address Line 2']      .= trim(
                    ' '.$address_fields['Address Postal Code']
                );
                $address_fields['Address Postal Code'] = '';
            }


            /*
            print_r($used_fields);
            print_r($address->data);
            print_r($address_fields);

            print $address->display();


            exit("\nError2\n");
            */
        }

    }

    if (!in_array('locality', $used_fields) and ($address_data['town'] != '' or $town_divs != '')) {


        //$address_fields['Address Locality']='';
        //$address_fields['Address Dependent Locality']='';

        if (in_array('addressLine2', $used_fields)) {

            if ($address_fields['Address Line 1'] == '' and $address_fields['Address Line 2'] == '') {
                $address_fields['Address Line 1'] .= $address_fields['Address Dependent Locality'];
                $address_fields['Address Line 2'] .= $address_fields['Address Locality'];

            } elseif ($address_fields['Address Line 1'] != '' and $address_fields['Address Line 2'] == '') {
                $address_fields['Address Line 2'] = preg_replace(
                    '/^, /', '', $address_fields['Address Dependent Locality'].', '.$address_fields['Address Locality']
                );

            } else {
                $address_fields['Address Line 2'] = preg_replace(
                    '/^, /', '', $address_fields['Address Dependent Locality'].', '.$address_fields['Address Locality']
                );

            }
        } else {

            print_r($used_fields);
            print_r($address_data);
            print_r($address_fields);


            exit("Error3\n");

        }


    }


    array_walk($address_fields, 'trim_value');
    //print "\n".$customer->id."\n";
    //print_r($address_fields);

    return $address_fields;
}


function get_address($address_id) {
    global $db;
    $address1    = '';
    $address2    = '';
    $town        = '';
    $postcode    = '';
    $country_div = '';
    $country     = '';
    $name        = '';

    $sql = sprintf("SELECT * FROM `drop`.`sales_flat_order_address` WHERE `entity_id` =%d", $address_id);

    if ($result = $db->query($sql)) {
        if ($row3 = $result->fetch()) {
            $town     = $row3['city'];
            $postcode = $row3['postcode'];
            $name     = $row3['firstname'];

            $array = preg_split('/$\R?^/m', $row3['street']);

            if (count($array) == 2) {
                $address1 = $array[0];
                $address2 = $array[1];

            } else {
                $address1 = $row3['street'];

            }

            $country = $row3['country_id'];

            if ($country == 'GB') {
                $country = 'United Kingdom';
            }

            $country_div = $row3['region'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    return array(
        $name,
        $address1,
        $address2,
        $town,
        $postcode,
        $country_div,
        $country
    );

}


function getMagentoAttNumber($attribute_code, $entity_type_id) {

    global $db;

    $sql = "SELECT `attribute_id` FROM `drop`.`eav_attribute` WHERE `attribute_code` LIKE '".$attribute_code."' AND `entity_type_id` =".$entity_type_id."  ";

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $Att_Got = $row['attribute_id'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    return $Att_Got;

}

function trim_value(&$value) {
    $value = trim(preg_replace('/\s+/', ' ', $value));
}


function get_payments($id) {
    global $db;
    $payments = array();
    $sql      = sprintf("SELECT * FROM `drop`.`sales_flat_order_payment` WHERE `parent_id` =%d", $id);

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $payments[] = $row;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    return $payments;
}

function get_history($id) {
    global $db;
    $history       = array();
    $date_order    = '';
    $date_invoiced = '';
    $date_refunded = '';
    $sql           = sprintf("SELECT * FROM `drop`.`sales_flat_order_status_history` WHERE `parent_id` =%d order by entity_id", $id);

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            if ($date_order == '' and $row['status'] == 'processing') {
                $date_order = $row['created_at'];
            }
            if ($date_invoiced == '' and $row['status'] == 'complete') {
                $date_invoiced = $row['created_at'];
            }
            if ($row['status'] == 'closed') {
                $date_refunded = $row['created_at'];
            }

            $history[] = $row;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    return array(
        $date_order,
        $date_invoiced,
        $date_refunded,
        $history
    );
}


