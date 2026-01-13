<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 July 2017 at 09:38:41 CEST, Trnava, Slavakia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
include_once 'utils/web_locale_functions.php';


$account = get_object('Account', 1);

$website = get_object('Website', $_SESSION['website_key']);


$current_locale = set_locate($website->get('Website Locale'));
//print $current_locale;

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
    case 'get_basket_html':
        $data = prepare_values($_REQUEST, array(
                                            'device_prefix' => array(
                                                'type'     => 'string',
                                                'optional' => true
                                            )
                                        ));

        get_basket_html($data, $customer);


        break;

    case 'get_items_html':
        $data = prepare_values($_REQUEST, array(
                                            'device_prefix' => array(
                                                'type'     => 'string',
                                                'optional' => true
                                            )
                                        ));

        get_items_html($data, $customer);


        break;


    case 'get_charges_info':

        get_charges_info($order);
        break;

    case 'special_instructions':
        $data = prepare_values($_REQUEST, array(
                                            'value' => array('type' => 'string'),

                                        ));
        update_special_instructions($data, $order, $editor);
        break;

    case 'invoice_address':
        $data = prepare_values($_REQUEST, array(
                                            'data' => array('type' => 'json array'),

                                        ));
        invoice_address($data, $order, $editor, $website);
        break;

    case 'delivery_address':
        $data = prepare_values($_REQUEST, array(
                                            'data' => array('type' => 'json array'),

                                        ));
        delivery_address($data, $order, $editor, $website);
        break;

    case 'web_toggle_charge':
        $data = prepare_values($_REQUEST, array(

                                            'charge_key' => array('type' => 'key'),
                                            'operation'  => array('type' => 'string'),

                                        ));
        web_toggle_charge($data, $editor, $db, $order, $customer, $website);
        break;

    case 'web_toggle_deal_component_choose_by_customer':
        $data = prepare_values($_REQUEST, array(
                                            'deal_component_key'                => array('type' => 'key'),
                                            'product_id'                        => array('type' => 'key'),
                                            'order_transaction_deal_bridge_key' => array('type' => 'order_transaction_deal_bridge_key'),


                                        ));
        web_toggle_deal_component_choose_by_customer($data, $editor, $db, $order, $customer);
        break;
}

function invoice_address($data, $order, $editor, $website)
{
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


        $address_data[$key] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', strip_tags($value));
    }



    $order->editor = $editor;
    $order->update(array('Order Invoice Address' => json_encode($address_data)));


    if ($order->get('Order State') == 'InBasket') {
        $order->fast_update(array(
                                'Order Last Updated by Customer' => gmdate('Y-m-d H:i:s')
                            ));
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
        'tax_description'         => $order->get('Tax Description'),

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

function delivery_address($data, $order, $editor, $website)
{
    $order->editor = $editor;


    if ($data['data']['order_for_collection']) {
        $order->update(array('Order For Collection' => 'Yes'));
    } else {
        $order->update(array('Order For Collection' => 'No'));
        unset($data['data']['order_for_collection']);

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
            $address_data[$key] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', strip_tags($value));
        }

        $order->update(array('Order Delivery Address' => json_encode($address_data)));
    }


    if ($order->get('Order State') == 'InBasket') {
        $order->fast_update(array(

                                'Order Last Updated by Customer' => gmdate('Y-m-d H:i:s')
                            ));
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
        'tax_description'         => $order->get('Tax Description'),

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

function update_special_instructions($data, $order, $editor)
{
    $order->editor = $editor;


    $order->fast_update(array('Order Customer Message' => strip_tags($data['value'], '<b><i><u><em><strong><br><p><ul><ol><li><a>')));


    if ($order->get('Order State') == 'InBasket') {
        $order->fast_update(array(

                                'Order Last Updated by Customer' => gmdate('Y-m-d H:i:s')
                            ));
    }


    $response = array(
        'state' => 200,


    );
    echo json_encode($response);
}

function get_charges_info($order)
{
    $response = array(
        'state' => 200,
        'title' => _('Charges'),
        'text'  => $order->get_charges_public_info()
    );
    echo json_encode($response);
}


function get_items_html($data, $customer)
{
    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    $website = get_object('Website', $_SESSION['website_key']);

    $theme = $website->get('Website Theme');

    /**
     * @var $order \Public_Order
     */
    $order = get_object('Order', $customer->get_order_in_process_key());


    $smarty->assign('edit', true);
    $smarty->assign('hide_title', true);
    $smarty->assign('items_data', $order->get_items());
    $smarty->assign('interactive_charges_data', $order->get_interactive_charges_data());


    $smarty->assign('interactive_deal_component_data', $order->get_interactive_deal_component_data());


    $smarty->assign('order', $order);


    $response = array(
        'state' => 200,
        'empty' => false,
        'html'  => $smarty->fetch($theme.'/_order_items.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl')
    );

    echo json_encode($response);
}

function get_basket_html($data, $customer)
{
    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $order = get_object('Order', $customer->get_order_in_process_key());


    $order->fast_update(array(
                            'Order Available Credit Amount' => $customer->get('Customer Account Balance'),
                            'Order Source Key'=>1
                        ));


    $website = get_object('Website', $_SESSION['website_key']);

    $theme = $website->get('Website Theme');


    $store = get_object('Store', $website->get('Website Store Key'));

    $webpage = $website->get_webpage('basket.sys');

    $content = $webpage->get('Content Data');


    $block_found = false;
    $block_key   = false;
    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'basket') {
            $block       = $_block;
            $block_key   = $_block_key;
            $block_found = true;
            break;
        }
    }

    if (!$block_found) {
        $response = array(
            'state' => 200,
            'html'  => '',
            'msg'   => 'no basket in webpage'
        );
        echo json_encode($response);
        exit;
    }
    $smarty->assign('order', $order);
    $smarty->assign('customer', $customer);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('key', $block_key);
    $smarty->assign('data', $block);
    $smarty->assign('labels', $website->get('Localised Labels'));


    require_once 'utils/get_addressing.php';
    require_once 'utils/get_countries.php';


    $countries = get_countries($website->get('Website Locale'));
    $smarty->assign('countries', $countries);

    $smarty->assign('zero_amount', money(0, $store->get('Store Currency Code')));


    if (!$order->id) {
        $response = array(
            'state' => 200,
            'empty' => true,
            'html'  => $smarty->fetch($theme.'/blk.basket_no_order.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
        );
    } else {
        list(
            $invoice_address_format, $invoice_address_labels, $invoice_used_fields, $invoice_hidden_fields, $invoice_required_fields, $invoice_no_required_fields
            ) = get_address_form_data(
            ($order->get('Order Invoice Address Country 2 Alpha Code') == '' ? ($customer->get('Customer Invoice Address Country 2 Alpha Code') == '' ? $store->get('Store Home Country Code 2 Alpha') : $customer->get(
                'Customer Invoice Address Country 2 Alpha Code'
            )) : $order->get('Order Invoice Address Country 2 Alpha Code'))

            ,
            $website->get('Website Locale')
        );


        $smarty->assign('invoice_address_labels', $invoice_address_labels);
        $smarty->assign('invoice_required_fields', $invoice_required_fields);
        $smarty->assign('invoice_no_required_fields', $invoice_no_required_fields);
        $smarty->assign('invoice_used_address_fields', $invoice_used_fields);


        list(
            $delivery_address_format, $delivery_address_labels, $delivery_used_fields, $delivery_hidden_fields, $delivery_required_fields, $delivery_no_required_fields
            ) = get_address_form_data(

            ($order->get('Order Delivery Address Country 2 Alpha Code') == '' ? ($customer->get('Customer Delivery Address Country 2 Alpha Code') == '' ? $store->get('Store Home Country Code 2 Alpha') : $customer->get(
                'Customer Delivery Address Country 2 Alpha Code'
            )) : $order->get('Order Delivery Address Country 2 Alpha Code')),
            $website->get('Website Locale')
        );

        $smarty->assign('delivery_address_labels', $delivery_address_labels);
        $smarty->assign('delivery_required_fields', $delivery_required_fields);
        $smarty->assign('delivery_no_required_fields', $delivery_no_required_fields);
        $smarty->assign('delivery_used_address_fields', $delivery_used_fields);


        $response = array(
            'state' => 200,
            'empty' => false,
            'html'  => $smarty->fetch($theme.'/blk.basket.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
        );
    }


    echo json_encode($response);
}


function web_toggle_charge($data, $editor, $db, $order, $customer, $website)
{
    $charge = get_object('Charge', $data['charge_key']);
    if (!$charge->id) {
        $response = array(
            'state' => 400,
            'msg'   => 'Charge not found',
        );

        echo json_encode($response);
        exit;
    }

    if ($charge->get('Store Key') != $order->get('Store Key')) {
        $response = array(
            'state' => 400,
            'msg'   => 'Charge not in same store as order',
        );

        echo json_encode($response);
        exit;
    }


    if ($data['operation'] == 'add_charge') {
        $transaction_data = $order->add_charge($charge);
    } else {
        $transaction_data = $order->remove_charge($charge);
    }


    if ($order->get('Order State') == 'InBasket') {
        $order->fast_update(array(

                                'Order Last Updated by Customer' => gmdate('Y-m-d H:i:s')
                            ));
    }


    $new_discounted_products = $order->get_discounted_products();
    foreach ($new_discounted_products as $key => $value) {
        $discounted_products[$key] = $value;
    }


    $hide         = array();
    $show         = array();
    $add_class    = array();
    $remove_class = array();

    $labels = $website->get('Localised Labels');

    if ($order->get('Shipping Net Amount') == 'TBC') {
        $shipping_amount = sprintf('<i class="fa error fa-exclamation-circle" title="" aria-hidden="true"></i> <small>%s</small>', (!empty($labels['_we_will_contact_you']) ? $labels['_we_will_contact_you'] : _('We will contact you')));
    } else {
        $shipping_amount = $order->get('Shipping Net Amount');
    }

    if ($order->get('Order Charges Net Amount') == 0) {
        $add_class['order_charges_container'] = 'very_discreet';

        $hide[] = 'order_charges_info';
    } else {
        $remove_class['order_charges_container'] = 'very_discreet';

        $show[] = 'order_charges_info';
    }


    if ($order->get('Order Items Discount Amount') == 0) {
        $hide[] = 'order_items_gross_container';
        $hide[] = 'order_items_discount_container';
    } else {
        $show[] = 'order_items_gross_container';
        $show[] = 'order_items_discount_container';
    }


    if ($order->get('Order Deal Amount Off') == 0) {
        $hide[] = 'Deal_Amount_Off_tr';
    } else {
        $show[] = 'Deal_Amount_Off_tr';
    }


    $class_html = array(
        'Deal_Amount_Off'         => $order->get('Deal Amount Off'),
        'order_items_gross'       => $order->get('Items Gross Amount'),
        'order_items_discount'    => $order->get('Basket Items Discount Amount'),
        'order_items_net'         => $order->get('Items Net Amount'),
        'order_net'               => $order->get('Total Net Amount'),
        'order_tax'               => $order->get('Total Tax Amount'),
        'order_charges'           => $order->get('Charges Net Amount'),
        'order_credits'           => $order->get('Net Credited Amount'),
        'available_credit_amount' => $order->get('Available Credit Amount'),
        'order_shipping'          => $shipping_amount,
        'order_total'             => $order->get('Total Amount'),
        'to_pay_amount'           => $order->get('Basket To Pay Amount'),
        'ordered_products_number' => $order->get('Products'),
        'order_amount'            => ((!empty($website->settings['Info Bar Basket Amount Type']) and $website->settings['Info Bar Basket Amount Type'] == 'items_net') ? $order->get('Items Net Amount') : $order->get('Total'))
    );


    $response = array(
        'state' => 200,


        'metadata' => array(
            'class_html'   => $class_html,
            'hide'         => $hide,
            'show'         => $show,
            'add_class'    => $add_class,
            'remove_class' => $remove_class,
            'new_otfs'     => $order->new_otfs,
            'deleted_otfs' => $order->deleted_otfs,

        ),


        'discounts' => ($order->data['Order Items Discount Amount'] != 0 ? true : false),
        'charges'   => ($order->data['Order Charges Net Amount'] != 0 ? true : false),

        'order_empty' => ($order->get('Products') == 0 ? true : false),

        'operation'        => $data['operation'],
        'transaction_data' => $transaction_data

    );

    echo json_encode($response);
}

function web_toggle_deal_component_choose_by_customer($data, $editor, $db, $order, $customer)
{
    $sql = sprintf('select * from `Order Transaction Deal Bridge`  OTDB  left join `Deal Dimension` DD  on (DD.`Deal Key`=OTDB.`Deal Key`)  where `Order Transaction Deal Key`=%d ', $data['order_transaction_deal_bridge_key']);


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            if ($row['Product ID'] == $data['product_id']) {
                $response = array(
                    'state' => 400,
                    'msg'   => 'nothing to change'
                );

                echo json_encode($response);
                exit;
            }


            if ($row['Order Key'] != $order->id) {
                $response = array(
                    'state' => 400,
                    'msg'   => 'wrong order'
                );

                echo json_encode($response);
                exit;
            }

            $deal_component = get_object('DealComponent', $data['deal_component_key']);

            $allowance_data = json_decode($deal_component->get('Deal Component Allowance'), true);


            if (!array_key_exists($data['product_id'], $allowance_data['options'])) {
                $response = array(
                    'state' => 400,
                    'msg'   => 'product not in offer'
                );

                echo json_encode($response);
                exit;
            }

            $product = get_object('Product', $data['product_id']);


            $customer->fast_update_json_field('Customer Metadata', 'DC_'.$deal_component->id, $product->id);

            //  $sql=sprintf('update `Order Transaction Fact` set `Product ID`  ')


            $deal_info = sprintf(
                "%s: %s, %s",
                ($row['Deal Name Label'] == '' ? _('Offer') : $row['Deal Name Label']),
                (!empty($row['Deal Term Label']) ? $row['Deal Term Label'] : ''),
                $deal_component->get('Deal Component Allowance Label')

            );


            $deal_info .= ' <span class="highlight"><i class="fa fa-plus-square padding_left_10"></i> '.sprintf('%d %s', $allowance_data['qty'], $product->get('Code')).'</span>';


            $sql = sprintf(
                'update `Order Transaction Deal Bridge` set `Product ID`=%d,`Product Key`=%d,`Category Key`=%d,`Order Transaction Deal Metadata`=%s,`Deal Info`=%s where `Order Transaction Deal Key`=%d  ',
                $product->id,
                $product->get('Product Current Key'),
                $product->get('Product Family Category Key'),
                prepare_mysql('{"selected": "'.$product->id.'"}'),
                prepare_mysql($deal_info),
                $data['order_transaction_deal_bridge_key']
            );

            $db->exec($sql);


            if ($order->get('Order State') == 'InBasket') {
                $order->fast_update(array(

                                        'Order Last Updated by Customer' => gmdate('Y-m-d H:i:s')
                                    ));
            }

            global $_locale;


            if ($product->get('Product Availability State') == 'OnDemand') {
                $stock = _('On demand');
            } else {
                if (is_numeric($product->get('Product Availability'))) {
                    $stock = number($product->get('Product Availability'));
                } else {
                    $stock = '?';
                }
            }


            $units    = $product->get('Product Units Per Case');
            $name     = $product->get('Product Name');
            $price    = $product->get('Product Price');
            $currency = $product->get('Product Currency');


            $description = '';
            if ($units > 1) {
                $description = number($units).'x ';
            }
            $description .= ' '.$name;
            if ($price > 0) {
                $description .= ' ('.money($price, $currency, $_locale).')';
            }


            $description .= '<br/>'.$deal_info;


            $sql = "update `Order Transaction Fact` set `Product ID`=?,`Product Key`=?,`OTF Category Family Key`=?,`OTF Category Department Key`=?  where `Order Transaction Fact Key`=?";

            $db->prepare($sql)->execute(array(
                                            $product->id,
                                            $product->get('Product Current Key'),
                                            $product->get('Product Family Category Key'),
                                            $product->get('Product Department Category Key'),
                                            $row['Order Transaction Fact Key']
                                        ));


            $transaction_deal_data = array(
                'otf_key'     => $row['Order Transaction Fact Key'],
                'product_id'  => $product->id,
                'Code'        => $product->get('Product Code'),
                'Description' => $description

            );


            $metadata = array(

                'class_html'  => array(
                    'Order_State'                   => $order->get('State'),
                    'Items_Net_Amount'              => $order->get('Items Net Amount'),
                    'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
                    'Charges_Net_Amount'            => $order->get('Charges Net Amount'),
                    'Total_Net_Amount'              => $order->get('Total Net Amount'),
                    'Total_Tax_Amount'              => $order->get('Total Tax Amount'),
                    'Total_Amount'                  => $order->get('Total Amount'),
                    'Total_Amount_Account_Currency' => $order->get('Total Amount Account Currency'),
                    'To_Pay_Amount'                 => $order->get('To Pay Amount'),
                    'Payments_Amount'               => $order->get('Payments Amount'),


                    'Order_Number_items' => $order->get('Number Items')

                ),
                //  'operations'    => $operations,
                'state_index' => $order->get('State Index'),
                'to_pay'      => $order->get('Order To Pay Amount'),
                'total'       => $order->get('Order Total Amount'),
                'charges'     => $order->get('Order Charges Net Amount'),


            );


            $response = array(
                'state'                 => 200,
                'metadata'              => $metadata,
                'transaction_deal_data' => $transaction_deal_data
            );
            echo json_encode($response);
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
}
