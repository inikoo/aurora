<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 July 2018 at 11:35:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


require_once 'keyring/key.php';
include_once 'utils/public_object_functions.php';

include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/network_functions.php';

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
session_start();

if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$logged_in = !empty($_SESSION['logged_in']);

if (!isset($db)) {
    require 'keyring/dns.php';
    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
$website = get_object('Website', $_SESSION['website_key']);


$account = get_object('Account', 1);

require_once 'utils/ar_web_common.php';


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


    case 'unsubscribe':
        $data = prepare_values(
            $_REQUEST, array(
                         'data'          => array('type' => 'json array'),
                         'selector'      => array('type' => 'string'),
                         'authenticator' => array('type' => 'string'),

                     )
        );
        unsubscribe($db, $data, $editor,$account);
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


function unsubscribe($db, $data, $editor,$account) {


    include_once('class.WebAuth.php');
    $auth = new WebAuth();

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
        } elseif ($key == 'postal_marketing') {
            $key   = 'Customer Send Postal Marketing';
            $value = ($value ? 'Yes' : 'No');
        }
        $update_data[$key] = $value;

    }

    $old_email_marketing = $unsubscribe_customer->get('Customer Send Email Marketing');
    $old_newsletters     = $unsubscribe_customer->get('Customer Send Newsletter');


    $unsubscribe_customer->editor = $editor;
    $unsubscribe_customer->update($update_data);


    if (($old_email_marketing == 'Yes' and $unsubscribe_customer->get('Customer Send Email Marketing') == 'No') or ($old_newsletters == 'Yes' and $unsubscribe_customer->get('Customer Send Newsletter') == 'No')) {
        $email_tracking = get_object('Email_Tracking', $data['selector']);
        $email_tracking->fast_update(
            array(
                'Email Tracking Unsubscribed' => 'Yes'
            )
        );

        include_once 'utils/new_fork.php';
        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'                    => 'update_sent_emails_data',
            'email_template_key'      => $email_tracking->get('Email Tracking Email Template Key'),
            'email_template_type_key' => $email_tracking->get('Email Tracking Email Template Type Key'),
            'email_mailshot_key'      => $email_tracking->get('Email Tracking Email Mailshot Key'),

        ), $account->get('Account Code')
        );

    }


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