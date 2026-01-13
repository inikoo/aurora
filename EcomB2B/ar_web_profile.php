<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 July 2017 at 00:35:25 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';

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


//print_r($_REQUEST);

switch ($tipo) {


    case 'get_profile_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_profile_html($data, $customer, $db);


        break;
    case 'contact_details':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        contact_details($db, $data, $customer, $editor);
        break;

    case 'unsubscribe':
        $data = prepare_values(
            $_REQUEST, array(
                         'data'          => array('type' => 'json array'),
                         'selector'      => array('type' => 'string'),
                         'authenticator' => array('type' => 'string'),

                     )
        );
        unsubscribe($db, $data, $editor);
        break;

    case 'invoice_address':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        invoice_address($db, $data, $customer, $editor);
        break;
    case 'delivery_address':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        delivery_address($db, $data, $customer, $editor);
        break;


    case 'update_password':
        $data = prepare_values(
            $_REQUEST, array(
                         'pwd' => array('type' => 'string'),

                     )
        );
        update_password($db, $data, $editor);
        break;
    case 'poll':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),

                     )
        );
        update_poll($db, $data, $customer, $editor);
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

function invoice_address($db, $data, $customer, $editor) {


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


    $customer->editor = $editor;
    $customer->update(array('Customer Invoice Address' => json_encode($address_data)));


    echo json_encode(
        array(
            'state'    => 200,
            'metadata' => array(
                'class_html' => array(
                    'Tax_Number_Valid' => $customer->get('Tax Number Valid'),
                )
            )
        )
    );


}

function delivery_address($db, $data, $customer, $editor) {


    $customer->editor = $editor;


    if ($data['data']['delivery_address_link']) {
        $customer->update(array('Customer Delivery Address Link' => 'Billing'));

    } else {
        $customer->update(array('Customer Delivery Address Link' => 'None'));

        unset($data['data']['delivery_address_link']);

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

        $customer->update(array('Customer Delivery Address' => json_encode($address_data)));

    }


    echo json_encode(
        array(
            'state' => 200
        )
    );


}

function contact_details($db, $data, $customer, $editor) {


    $update_data = array();

    foreach ($data['data'] as $key => $value) {

        if ($key == 'company') {
            $key = 'Customer Company Name';
        } elseif ($key == 'contact_name') {
            $key = 'Customer Main Contact Name';
        } elseif ($key == 'email') {
            $key = 'Customer Main Plain Email';
        } elseif ($key == 'mobile') {
            $key = 'Customer Main Plain Mobile';
        } elseif ($key == 'registration_number') {
            $key = 'Customer Registration Number';
        } elseif ($key == 'tax_number') {
            $key = 'Customer Tax Number';
        } elseif ($key == 'newsletter') {
            $key   = 'Customer Send Newsletter';
            $value = ($value ? 'Yes' : 'No');
        } elseif ($key == 'email_marketing') {
            $key   = 'Customer Send Email Marketing';
            $value = ($value ? 'Yes' : 'No');
        } elseif ($key == 'basket_emails') {
            $key   = 'Customer Send Basket Emails';
            $value = ($value ? 'Yes' : 'No');
        } elseif ($key == 'postal_marketing') {
            $key   = 'Customer Send Postal Marketing';
            $value = ($value ? 'Yes' : 'No');
        }

        $update_data[$key] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', strip_tags($value));

    }

    //print_r($update_data);

    $customer->editor = $editor;
    $customer->update($update_data);


    echo json_encode(
        array(
            'state'    => 200,
            'metadata' => array(
                'class_html' => array(
                    'Tax_Number_Valid' => $customer->get('Tax Number Valid'),
                    'Customer_Name' => $customer->get('Customer Name'),

                )
            )
        )
    );


}

function update_password($db, $data, $editor) {


    $website_user         = get_object('User', $_SESSION['website_user_key']);
    $website_user->editor = $editor;


    if ($website_user->id) {

        $website_user->update(
            array(
                'Website User Password Hash' => password_hash($data['pwd'], PASSWORD_DEFAULT, array('cost' => 12)),


            ), 'no_history'
        );

        $website_user->update(
            array(
                'Website User Password' => $data['pwd'],


            ), 'no_history'
        );


        echo json_encode(
            array(
                'state' => 200
            )
        );
        exit;

    } else {
        echo json_encode(
            array(
                'state'      => 400,
                'error_type' => 'User not found'
            )
        );
        exit;
    }


}


function update_poll($db, $data, $customer, $editor) {

    $customer->editor = $editor;
    foreach ($data['data'] as $_key => $value) {

        if (preg_match('/^poll_(\d+)/i', $_key, $matches)) {

            $poll_key = $matches[1];

            $value=preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', strip_tags($value));

            $customer->update(array('Customer Poll Query '.$poll_key => $value));


        }
    }

    echo json_encode(
        array(
            'state' => 200
        )
    );
    exit;
}


function get_profile_html($data, $customer, $db) {


    $smarty = new Smarty();
    $smarty->caching_type = 'redis';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $order = get_object('Order', $customer->get_order_in_process_key());

    $website = get_object('Website', $_SESSION['website_key']);
    $store   = get_object('Store', $website->get('Website Store Key'));

    $webpage = $website->get_webpage('profile.sys');

    $content = $webpage->get('Content Data');


    $block_found = false;
    $block_key   = false;
    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'profile') {
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
            'msg'   => 'no profile in webpage'
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
    $smarty->assign('logged_in', true);
    $smarty->assign('order_key', $order->id);


    require_once 'utils/get_addressing.php';
    require_once 'utils/get_countries.php';

    list(
        $invoice_address_format, $invoice_address_labels, $invoice_used_fields, $invoice_hidden_fields, $invoice_required_fields, $invoice_no_required_fields
        ) = get_address_form_data($customer->get('Customer Invoice Address Country 2 Alpha Code'), $website->get('Website Locale'));

    $smarty->assign('invoice_address_labels', $invoice_address_labels);
    $smarty->assign('invoice_required_fields', $invoice_required_fields);
    $smarty->assign('invoice_no_required_fields', $invoice_no_required_fields);
    $smarty->assign('invoice_used_address_fields', $invoice_used_fields);


    list(
        $delivery_address_format, $delivery_address_labels, $delivery_used_fields, $delivery_hidden_fields, $delivery_required_fields, $delivery_no_required_fields
        ) = get_address_form_data($customer->get('Customer Invoice Address Country 2 Alpha Code'), $website->get('Website Locale'));

    $smarty->assign('delivery_address_labels', $delivery_address_labels);
    $smarty->assign('delivery_required_fields', $delivery_required_fields);
    $smarty->assign('delivery_no_required_fields', $delivery_no_required_fields);
    $smarty->assign('delivery_used_address_fields', $delivery_used_fields);


    $countries = get_countries($website->get('Website Locale'));
    $smarty->assign('countries', $countries);


    $smarty->assign('poll_queries', $website->get_poll_queries($webpage, $customer->id));


    $response = array(
        'state' => 200,
        'html'  => $smarty->fetch('theme_1/blk.profile.theme_1.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
    );


    echo json_encode($response);

}


function unsubscribe($db, $data, $editor) {


    include_once('class.WebAuth.php');
    $auth = new WebAuth($db);

    list($unsubscribe_subject_type,$unsubscribe_customer_key) = $auth->get_customer_from_unsubscribe_link($data['selector'], $data['authenticator']);


    if ($unsubscribe_customer_key != '') {
        $unsubscribe_customer = get_object('Customer', $unsubscribe_customer_key);
        if (!$unsubscribe_customer->id) {
            echo json_encode(
                array(
                    'state'      => 400,
                    'error_type' => 'Customer not found'
                )
            );
            exit;
        }
    } else {
        echo json_encode(
            array(
                'state'      => 400,
                'error_type' => 'Customer not found'
            )
        );
        exit;
    }

    $update_data = array();


    foreach ($data['data'] as $key => $value) {

        if ($key == 'newsletter') {
            $key   = 'Customer Send Newsletter';
            $value = ($value ? 'Yes' : 'No');
        } elseif ($key == 'email_marketing') {
            $key   = 'Customer Send Email Marketing';
            $value = ($value ? 'Yes' : 'No');
        }elseif ($key == 'basket_emails') {
            $key   = 'Customer Send Basket Emails';
            $value = ($value ? 'Yes' : 'No');
        } elseif ($key == 'postal_marketing') {
            $key   = 'Customer Send Postal Marketing';
            $value = ($value ? 'Yes' : 'No');
        }
        $update_data[$key] = $value;

    }

    //print_r($update_data);

    $unsubscribe_customer->editor = $editor;
    $unsubscribe_customer->update($update_data);


    echo json_encode(
        array(
            'state'    => 200,
            'metadata' => array(
                'class_html' => array()
            )
        )
    );


}


?>