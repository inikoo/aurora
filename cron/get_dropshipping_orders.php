<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2013 Inikoo



include_once 'dropshipping_common_functions.php';

$dropshipping_location_key = 15221;

require_once 'common.php';
include 'class.Customer.php';
include 'class.PartLocation.php';
include 'class.TaxCategory.php';


require_once 'class.Country.php';
require_once 'utils/get_addressing.php';
include_once 'utils/data_entry_picking_aid.class.php';


$con_drop = @mysql_connect($dns_host, $dns_user, $dns_pwd);
if (!$con_drop) {
    print "Error can not connect with dropshipping database server\n";
    exit;
}
$db2 = @mysql_select_db("drop", $con_drop);
if (!$db2) {
    print "Error can not access the database in drop \n";
    exit;
}

$con = @mysql_connect($dns_host, $dns_user, $dns_pwd);

if (!$con) {
    print "Error can not connect with database server\n";
    exit;
}
//$dns_db='dw_avant';
$db = @mysql_select_db("dw", $con);
if (!$db) {
    print "Error can not access the database\n";
    exit;
}

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (reading data from Magento)'
);
$store  = get_object('Store', 9);


$credits = array();

//$sql= "SELECT * FROM `drop`.`sales_flat_order` where entity_id=141387	";
$sql = "SELECT * FROM `drop`.`sales_flat_order`  ";
$sql = "SELECT * FROM `drop`.`sales_flat_order` where increment_id='DS77140'";
$res = mysql_query($sql, $con_drop);

while ($row = mysql_fetch_assoc($res)) {
    $shipping_net = 0;

    //print_r($row);

    $store_code    = $store->data['Store Code'];
    $order_data_id = $row['entity_id'];

    $sql   = sprintf(
        "select * from `Order Import Metadata` where `Metadata`=%s and `Import Date`>=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at'])

    );
    $resxx = mysql_query($sql);
    if ($rowxx = mysql_fetch_assoc($resxx)) {
        continue;
    }
    //print "Entity: ".$row['entity_id']."\n";

    //delete_old_data();
    //print_r($row);

    //continue;
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
    //print $row['state']."\n";
    $sql  = sprintf("select created_at from `drop`.sales_flat_order_status_history where parent_id=%d and status in ('complete')   ", $row['entity_id']);
    $res2 = mysql_query($sql, $con_drop);
    //print $sql. "\n";
    //echo mysql_errno($con_drop) . ": " . mysql_error($con_drop) . "\n";
    if ($row2 = mysql_fetch_assoc($res2)) {
        $date_inv = $row2['created_at'];
    } else {
        $date_inv = $row['created_at'];
    }


    print $row['increment_id'].' '.$row['updated_at']."\n";


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

        $date_order   = $row['created_at'];
        $shipping_net = $header_data['shipping'];


        $charges_net = 0;

        $data_dn_transactions         = array();
        $discounts_with_order_as_term = array();


        //print "C:".$customer->id."\n";
        $sql  = sprintf(
            "select * from `drop`.`sales_flat_order_item` WHERE `order_id`=%d ", $row['entity_id']
        );
        $res2 = mysql_query($sql, $con_drop);
        while ($row2 = mysql_fetch_assoc($res2)) {

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

            $sql = sprintf('select `Product ID` from `Product Dimension` where  `Product Store Key`=%d and  `Product Code`="%s"  and `Product Status`="Active" ', $store->id, addslashes($row2['sku']));

            $resxx2 = mysql_query($sql);
            if ($rowxx2 = mysql_fetch_assoc($resxx2)) {
                $product = get_object('Product', $rowxx2['Product ID']);
            } else {


                print 'product not found: '.$row2['sku']."\n";
                exit();


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
                'discount'              => '',
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


            create_dn_invoice_transactions($transaction, $product, $used_parts_sku);

        }

        list($address1, $address2, $town, $postcode, $country_div, $country) = get_address($row['shipping_address_id']);

        $country = new Country('find', $country);


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

            ), $customer->get('Customer Main Contact Name'), $customer->get('Contact Name'), 'GB'
        );


        $data                                  = array();
        $editor['Date']                        = $row['created_at'];
        $data['editor']                        = $editor;
        $data['order_date']                    = $row['created_at'];
        $data['order id']                      = $row['increment_id'];
        $data['order customer message']        = $row['customer_note'];
        $data['order original data source']    = 'Magento';
        $data['Order For']                     = 'Customer';
        $data['Order Main Source Type']        = 'Internet';
        $data['Delivery Note Dispatch Method'] = 'Shipped';
        $data['staff sale']                    = 'no';
        $data['staff sale key']                = 0;
        //$data['Order Ship To Key']=$ship_to->id;
        $data['Order Customer Key'] = $customer->id;

        $data['Order Type'] = 'Order';

        //print_r($data_dn_transactions);
        //		print_r($row);

        //		print $data['order id']."   \n";

        print_r($data);
        //print_r($data_dn_transactions);

        //print_r($customer->id);


        $order = $customer->create_order();


        $delivery_address_fields = array();

        $order->update_field_switcher('Order_Delivery Address', $delivery_address_fields, 'no_history');


        $order->update_shipping_amount($shipping_net);
        $order->fast_update(
            array(
                'Order Public ID'    => $data['order id'],
                'Order File As'      => $data['order id'],
                'Order Sticky Note'  => $data['order customer message'],
                'Order Date'         => $data['order_date'],
                'Order Created Date' => $data['order_date'],
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
                where  `Order Transaction Fact Key`=%d', prepare_mysql($data['order_date']), prepare_mysql($data['order_date']), $transaction['otf_key']
            );

            $db->exec($sql);

        }

        $order->update(
            array(
                'Order State' => 'InProcess',
            ), 'no_history'
        );


        $order->fast_update(
            array(
                'Order Submitted by Customer Date' => $data['order_date'],
                'Order Date'                       => $data['order_date'],
            )
        );

        if ($row['state'] == 'complete' or $row['state'] == 'closed') {


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
                            'Date'         => gmdate('Y-m-d H:i:s'),
                            'Subject'      => 'System',
                            'Subject Key'  => 0,
                            'Author Name'  => 'Script (reading data from Magento)'
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


            $order->update(
                array(
                    'Order State' => 'InWarehouse',
                ), 'no_history'
            );

            $items = array();
            $sql   = sprintf(
                'select PD.`Part SKU`,`Required`+`Given` as required,L.`Location Key`,`Inventory Transaction Key`  from `Inventory Transaction Fact` ITF  left join `Part Dimension` PD on (ITF.`Part SKU`=PD.`Part SKU`)  LEFT JOIN  `Part Location Dimension` PL ON  (ITF.`Location Key`=PL.`Location Key` and ITF.`Part SKU`=PL.`Part SKU`) left join `Location Dimension` L on (L.`Location Key`=ITF.`Location Key`)
    left join `Order Transaction Fact` on (`Order Transaction Fact Key`= `Map To Order Transaction Fact Key`) where ITF.`Delivery Note Key`=%d ', $order->get('Order Delivery Note Key')
            );


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

            print_r($_data);

            $data_entry_picking_aid = new data_entry_picking_aid($_data, $editor, $db, $account);


            $validation = $data_entry_picking_aid->parse_input_data();
            if (!$validation['valid']) {


                echo json_encode($validation['response']);

            }


            $data_entry_picking_aid->update_delivery_note();


            $data_entry_picking_aid->process_transactions();

            $data_entry_picking_aid->finish_packing();


        } elseif ($row['state'] == 'canceled') {
            $order->cancel('', $date_order);

        }

        exit;


        $sql = sprintf(
            "INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at']), prepare_mysql($row['increment_id']), prepare_mysql($row['updated_at'])
        );

        $db->exec($sql);


    } else {
        print $row['increment_id'].' '.$row['customer_id']." customer not found\n";
    }


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


    $tax_rates = array();
    $tax_names = array();
    $sql       = sprintf("select * from `Tax Category Dimension` ");
    $res       = mysql_query($sql);
    while ($row = mysql_fetch_assoc($res)) {
        $tax_rates[$row['Tax Category Code']] = $row['Tax Category Rate'];
        $tax_names[$row['Tax Category Code']] = $row['Tax Category Name'];
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
    $header_data['staff sale key']                = 0;;
    $header_data['collection']      = 'No';
    $header_data['shipper_code']    = '';
    $header_data['staff sale']      = 'No';
    $header_data['showroom']        = 'No';
    $header_data['staff sale name'] = '';


    return $header_data;
}


function create_dn_invoice_transactions($transaction, $product, $used_parts_sku) {
    global $date_order, $products_data, $data_invoice_transactions, $data_dn_transactions, $estimated_w;


    if ($transaction['order'] > 0) {


        if ($transaction['order'] < $transaction['reorder']) {
            $transaction['reorder'] = $transaction['order'];
        }

        $products_data[] = array(
            'Product Key'      => $product->id,
            'Estimated Weight' => $product->data['Product Package Weight'] * $transaction['order'],
            'qty'              => $transaction['order'],
            'gross_amount'     => $transaction['order'] * $transaction['price'],
            'discount_amount'  => $transaction['order'] * $transaction['price'] * $transaction['discount'],
            'units_per_case'   => $product->data['Product Units Per Case']
        );

        //print_r($transaction);

        $net_amount   = round(($transaction['order'] - $transaction['reorder']) * $transaction['price'] * (1 - $transaction['discount']), 2);
        $gross_amount = round(($transaction['order'] - $transaction['reorder']) * $transaction['price'], 2);
        $net_discount = -$net_amount + $gross_amount;

        if ($net_amount > 0) {
            //$product->update_last_sold_date($date_order);
            //$product->update_first_sold_date($date_order);
            //$product->update_for_sale_since(date("Y-m-d H:i:s",strtotime("$date_order -1 second")));

            /*
                        if ($product->updated_field['Product For Sale Since Date']) {
                            $_date_order=date("Y-m-d H:i:s",strtotime("$date_order -2 second"));
                            $sql=sprintf("update `History Dimension` set `History Date`=%s  where `Action`='created' and `Direct Object`='Product' and `Direct Object Key`=%d  ",prepare_mysql($_date_order),$product->pid);
                            mysql_query($sql);


                        }
            */

        }


        $data_invoice_transactions[] = array(
            'original_amount'       => round(($transaction['order'] - $transaction['reorder']) * $transaction['original_price'] * (1 - $transaction['discount']), 2),
            'Product Key'           => $product->id,
            'invoice qty'           => $transaction['order'] - $transaction['reorder'],
            'gross amount'          => $gross_amount,
            'discount amount'       => $net_discount,
            'current payment state' => 'Paid',
            'description'           => $transaction['description'].($transaction['code'] != '' ? " (".$transaction['code'].")" : ''),
            'credit'                => $transaction['credit']


        );
        // print_r($data_invoice_transactions);
        $estimated_w += $product->data['Product Package Weight'] * ($transaction['order'] - $transaction['reorder']);
        //print "$estimated_w ".$product->data['Product Package Weight']." ".($transaction['order']-$transaction['reorder'])."\n";


        $data_dn_transactions[] = array(
            'otf_key'                            => '',
            'Code'                               => $product->get('Product Code'),
            'Product Key'                        => $product->id,
            'Estimated Weight'                   => $product->data['Product Package Weight'] * ($transaction['order'] - $transaction['reorder']),
            'Product ID'                         => $product->data['Product ID'],
            'Delivery Note Quantity'             => $transaction['order'] - $transaction['reorder'],
            'Current Autorized to Sell Quantity' => $transaction['order'],
            'Shipped Quantity'                   => $transaction['order'] - $transaction['reorder'],
            'No Shipped Due Out of Stock'        => $transaction['reorder'],
            'Order Quantity'                     => $transaction['order'],
            'No Shipped Due No Authorized'       => 0,
            'No Shipped Due Not Found'           => 0,
            'No Shipped Due Other'               => 0,
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
        $products_data[]             = array(
            'Product Key'      => $product->id,
            'qty'              => 0,
            'bonus qty'        => $transaction['bonus'],
            'gross_amount'     => 0,
            'discount_amount'  => 0,
            'Estimated Weight' => 0,
            'units_per_case'   => $product->data['Product Units Per Case']
        );
        $data_invoice_transactions[] = array(
            'Product Key'           => $product->id,
            'credit'                => 0,
            'original_amount'       => 0,
            'description'           => $transaction['description'].($transaction['code'] != '' ? " (".$transaction['code'].")" : ''),
            'invoice qty'           => $transaction['bonus'],
            'gross amount'          => ($transaction['bonus']) * $transaction['price'],
            'discount amount'       => ($transaction['bonus']) * $transaction['price'],
            'current payment state' => 'No Applicable'
        );

        $estimated_w            += $product->data['Product Package Weight'] * $transaction['bonus'];
        $data_dn_transactions[] = array(
            'otf_key'                            => '',
            'Code'                               => $product->code,
            'Product Key'                        => $product->id,
            'Product ID'                         => $product->data['Product ID'],
            'Delivery Note Quantity'             => $transaction['bonus'],
            'Current Autorized to Sell Quantity' => $transaction['bonus'],
            'Shipped Quantity'                   => $transaction['bonus'],
            'Order Quantity'                     => 0,
            'No Shipped Due Out of Stock'        => 0,
            'No Shipped Due No Authorized'       => 0,
            'No Shipped Due Not Found'           => 0,
            'No Shipped Due Other'               => 0,
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
