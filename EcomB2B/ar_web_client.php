<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 July 2017 at 00:35:25 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';

require_once __DIR__.'/utils/get_addressing.php';


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


    case 'get_client_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'id'            => array('type' => 'numeric'),
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_client_html($data, $customer);


        break;
    case 'update_customer_client_details':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'  => array('type' => 'key'),
                         'data' => array('type' => 'json array'),

                     )
        );
        update_customer_client_details($data, $customer->id, $editor);
        break;


    case 'update_contact_address':
        $data = prepare_values(
            $_REQUEST, array(
                         'key'  => array('type' => 'key'),
                         'data' => array('type' => 'json array'),

                     )
        );
        update_contact_address($data, $customer->id, $editor);
        break;

    case 'update_client_reference':

        $data = prepare_values(
            $_REQUEST, array(
                         'reference'  => array('type' => 'string'),
                         'client_key' => array('type' => 'key'),
                     )
        );

        update_client_reference($data, $db, $customer);
    case 'delete_client':

        $data = prepare_values(
            $_REQUEST, array(
                         'client_key' => array('type' => 'key'),
                     )
        );

        delete_client($data, $db, $customer);
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}

/**
 * @param $data         array
 * @param $customer_key integer
 * @param $editor       array
 */
function update_contact_address($data, $customer_key, $editor) {


    $customer_client = get_object('Customer_Client', $data['key']);
    if (!$customer_client->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }
    if ($customer_client->get('Customer Client Customer Key') != $customer_key) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }


    $customer_client->editor = $editor;


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

    $customer_client->update(array('Customer Client Contact Address' => json_encode($address_data)));


    echo json_encode(
        array(
            'state'    => 200,
            'metadata' => array(
                'class_html' => [
                    'Customer_Client_Contact_Address' => $customer_client->get('Contact Address Formatted'),

                ]
            )
        )
    );

}

/**
 * @param $data
 * @param $customer_key integer
 * @param $editor       array
 */
function update_customer_client_details($data, $customer_key, $editor) {


    $customer_client = get_object('Customer_Client', $data['key']);
    if (!$customer_client->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }
    if ($customer_client->get('Customer Client Customer Key') != $customer_key) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }


    $update_data = array();

    foreach ($data['data'] as $key => $value) {

        if ($key == 'company') {
            $key = 'Customer Client Company Name';
        } elseif ($key == 'contact_name') {
            $key = 'Customer Client Main Contact Name';
        } elseif ($key == 'email') {
            $key = 'Customer Client Main Plain Email';
        } elseif ($key == 'mobile') {
            $key = 'Customer Client Main Plain Mobile';
        } elseif ($key == 'code') {
            $key = 'Customer Client Code';
        }
        $update_data[$key] = $value;

    }


    $customer_client->editor = $editor;
    $customer_client->update($update_data);

    $hide = [];
    $show = [];

    if ($customer_client->get('Customer Client Company Name') == '') {
        $hide[] = 'Customer_Client_Company_Name_display';
    } else {
        $show[] = 'Customer_Client_Company_Name_display';
    }
    if ($customer_client->get('Customer Client Main Contact Name') == '') {
        $hide[] = 'Customer_Client_Main_Contact_Name_display';
    } else {
        $show[] = 'Customer_Client_Main_Contact_Name_display';
    }
    if ($customer_client->get('Customer Client Main Plain Email') == '') {
        $hide[] = 'Subject_Email_display';
    } else {
        $show[] = 'Subject_Email_display';
    }
    if ($customer_client->get('Main XHTML Mobile') == '') {
        $hide[] = 'Subject_Mobile_display';
    } else {
        $show[] = 'Subject_Mobile_display';
    }
    if ($customer_client->get('Main XHTML Telephone') == '') {
        $hide[] = 'Subject_Telephone_display';
    } else {
        $show[] = 'Subject_Telephone_display';
    }

    echo json_encode(
        array(
            'state'      => 200,
            'metadata'   => array(
                'class_html' => [
                    'Customer_Client_Main_Plain_Mobile'    => $customer_client->get('Main XHTML Mobile'),
                    'Customer_Client_Main_Plain_Telephone' => $customer_client->get('Main XHTML Telephone'),
                    'Subject_Email'                        => ($customer_client->get('Customer Client Main Plain Email') == ''
                        ? '' : '<a href="'.$customer_client->get('Customer Client Main Plain Email').'">'.$customer_client->get('Customer Client Main Plain Email').'</a>'),
                    'Customer_Client_Main_Contact_Name'    => $customer_client->get('Customer Client Main Contact Name'),
                    'Customer_Client_Code'                 => $customer_client->get('Customer Client Code'),
                    'Customer_Client_Name'                 => $customer_client->get('Customer Client Name'),
                    'Customer_Client_Contact_Address'      => $customer_client->get('Contact Address Formatted'),
                    'Customer_Client_Company_Name'         => $customer_client->get('Customer Client Company Name'),
                    'Formatted_Client_Code'                => $customer_client->get('Formatted Client Code'),
                    '.client_nav'                          => $customer_client->get('Formatted Client Code')

                ],
                'hide'       => $hide,
                'show'       => $show,
            ),
            'client_nav' => [
                'label' => $customer_client->get('Customer Client Code'),
                'title' => htmlspecialchars($customer_client->get('Customer Client Name'))

            ]
        )
    );


}

function get_client_html($data, $customer) {


    $smarty = new Smarty();
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');


    //print_r($data);


    $website         = get_object('Website', $_SESSION['website_key']);
    $store           = get_object('Store', $website->get('Website Store Key'));
    $customer_client = get_object('Customer_Client', $data['id']);




    if(!$customer_client->id){
        $response = array(
            'state' => 404,

        );
        echo json_encode($response);
        exit;
    }

    //print_r($customer_client);


    $webpage = $website->get_webpage('client.sys');
    $content = $webpage->get('Content Data');

    $block       = '';
    $block_found = false;
    $block_key   = false;
    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'client') {
            $block       = $_block;
            $block_key   = $_block_key;
            $block_found = true;
            break;
        }
    }

    if (!$block_found) {
        $response = array(
            'state' => 400,
            'html'  => '',
            'msg'   => 'no profile in webpage'
        );
        echo json_encode($response);
        exit;
    }

    $smarty->assign('customer_client', $customer_client);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('key', $block_key);
    $smarty->assign('data', $block);
    $smarty->assign('labels', $website->get('Localised Labels'));
    $smarty->assign('logged_in', true);


    require_once __DIR__.'/utils/get_addressing.php';
    require_once __DIR__.'/utils/get_countries.php';


    list(
        $delivery_address_format, $delivery_address_labels, $delivery_used_fields, $delivery_hidden_fields, $delivery_required_fields, $delivery_no_required_fields
        ) = get_address_form_data($customer->get('Customer Invoice Address Country 2 Alpha Code'), $website->get('Website Locale'));

    $smarty->assign('delivery_address_labels', $delivery_address_labels);
    $smarty->assign('delivery_required_fields', $delivery_required_fields);
    $smarty->assign('delivery_no_required_fields', $delivery_no_required_fields);
    $smarty->assign('delivery_used_address_fields', $delivery_used_fields);


    $countries = get_countries($website->get('Website Locale'));
    $smarty->assign('countries', $countries);


    $response = array(
        'state'      => 200,
        'html'       => $smarty->fetch('theme_1/blk.client.theme_1.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
        'client_nav' => [
            'label' => '<a href="/client.sys?id='.$customer_client->id.'">'.($customer_client->get('Customer Client Code') == '' ? '<span class="italic">'.sprintf('%05d', $customer_client->id).'</span>' : $customer_client->get('Customer Client Code')).'</a>',
            'title' => htmlspecialchars($customer_client->get('Customer Client Name'))

        ]
    );


    echo json_encode($response);

}

/**
 * @param $data
 * @param $db       \PDO
 * @param $customer \Public_Customer
 */
function update_client_reference($data, $db, $customer) {

    $reference = trim($data['reference']);


    if ($reference != '') {
        $sql  = "select `Customer Client Key` from `Customer Client Dimension` where `Customer Client Customer Key`=? and `Customer Client Key`!=? and `Customer Client Code`=?  ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $customer->id,
                $data['client_key'],
                $data['reference']
            )
        );
        if ($row = $stmt->fetch()) {
            echo json_encode(
                array(
                    'state'     => 200,
                    'ok'        => false,
                    'reference' => $reference

                )
            );
            exit;
        }
    }

    $sql = "update `Customer Client Dimension`  set `Customer Client Code`=?  where `Customer Client Customer Key`=? and `Customer Client Key`=?   ";
    $db->prepare($sql)->execute(
        array(
            ($reference == '' ? null : $reference),
            $customer->id,
            $data['client_key'],
        )
    );


    echo json_encode(
        array(
            'state'               => 200,
            'ok'                  => true,
            'reference'           => $reference,
            'formatted_reference' => ($reference == '' ? _('Add reference') : $reference)

        )
    );
    exit;


}

/**
 * @param $data
 * @param $db       \PDO
 * @param $customer \Public_Customer
 */
function delete_client($data, $db, $customer) {


    $customer_client = get_object('Customer_Client', $data['client_key']);
    if (!$customer_client->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }
    if ($customer_client->get('Customer Client Customer Key') != $customer->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Customer not found'
        );
        echo json_encode($response);
        exit;
    }




        $customer_client->delete();


    echo json_encode(
        array(
            'state'               => 200,
            'ok'                  => true,


        )
    );
    exit;


}