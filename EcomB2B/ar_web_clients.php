<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Sat 26 Oct 2019 01:32:00 +0800 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';

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

    case 'new_customer_client':
        $data = prepare_values(
            $_REQUEST, array(
                         'data'      => array('type' => 'json array'),
                     )
        );
        new_customer_client($data,$customer, $db,$editor);
        break;
    case 'get_clients':
        get_clients($customer, $db);
        break;

        break;

}

/**
 * @param $data
 * @param $customer \Public_Customer
 * @param $db \PDO
 * @param $editor
 *
 * @throws \Exception
 */
function new_customer_client($data,$customer, $db,$editor) {

    include_once 'utils/new_fork.php';



    $customer->editor = $editor;
    $raw_data      = $data['data'];


    print_r($raw_data);
    exit;


    if ($customer->id) {
        $customer_data = array(
            'Customer Main Contact Name'   => $raw_data['name'],
            'Customer Company Name'        => $raw_data['organization'],
            'Customer Main Plain Email'    => $raw_data['email'],
            'Customer Main Plain Mobile'   => $raw_data['tel'],

        );

        if (array_key_exists('locality', $raw_data)) {
            $customer_data['Customer Contact Address locality'] = $raw_data['locality'];
        }
        if (array_key_exists('postalCode', $raw_data)) {
            $customer_data['Customer Contact Address postalCode'] = $raw_data['postalCode'];
        }
        if (array_key_exists('addressLine1', $raw_data)) {
            $customer_data['Customer Contact Address addressLine1'] = $raw_data['addressLine1'];
        }
        if (array_key_exists('addressLine2', $raw_data)) {
            $customer_data['Customer Contact Address addressLine2'] = $raw_data['addressLine2'];
        }
        if (array_key_exists('administrativeArea', $raw_data)) {
            $customer_data['Customer Contact Address administrativeArea'] = $raw_data['administrativeArea'];
        }
        if (array_key_exists('dependentLocality', $raw_data)) {
            $customer_data['Customer Contact Address dependentLocality'] = $raw_data['dependentLocality'];
        }
        if (array_key_exists('sortingCode', $raw_data)) {
            $customer_data['Customer Contact Address sortingCode'] = $raw_data['sortingCode'];
        }
        if (array_key_exists('country', $raw_data)) {
            $customer_data['Customer Contact Address country'] = $raw_data['country'];
        }

        if (isset($raw_data['subscription']) and $raw_data['subscription'] == 'on') {
            $customer_data['Customer Send Newsletter']       = 'Yes';
            $customer_data['Customer Send Email Marketing']  = 'Yes';
            $customer_data['Customer Send Postal Marketing'] = 'Yes';

        } else {
            $customer_data['Customer Send Newsletter']       = 'No';
            $customer_data['Customer Send Email Marketing']  = 'No';
            $customer_data['Customer Send Postal Marketing'] = 'No';
        }


        list($customer, $website_user) = $store->create_customer($customer_data, array('Website User Password' => $raw_data['new-password']));

        if ($store->new_customer and $store->new_website_user) {


            foreach ($raw_data as $_key => $value) {

                if (preg_match('/^poll_(\d+)/i', $_key, $matches)) {
                    $poll_key = $matches[1];
                    $customer->update(array('Customer Poll Query '.$poll_key => $value), 'no_history');
                }


            }




            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'         => 'customer_registered',
                'customer_key' => $customer->id,
                'website_key'  => $website->id
            ), $account->get('Account Code')
            );


            if ($website->get('Website Registration Type') != 'ApprovedOnly') {

                include_once('class.WebAuth.php');
                $auth = new WebAuth();

                list($logged_in, $website_user_log_key) = $auth->authenticate_from_register($website_user->id, $customer->id, $store->get('Store Website Key'));

                if ($logged_in) {
                    $_SESSION['logged_in']            = true;
                    $_SESSION['customer_key']         = $customer->id;
                    $_SESSION['website_user_key']     = $website_user->id;
                    $_SESSION['website_user_log_key'] = $website_user_log_key;


                    require_once "external_libs/random/lib/random.php";
                    $selector      = base64_encode(random_bytes(9));
                    $authenticator = random_bytes(33);

                    setcookie(
                        'rmb', $selector.':'.base64_encode($authenticator), time() + 864000, '/'
                    //,'',
                    //true, // TLS-only
                    //true  // http-only
                    );


                    // print_r($_SESSION);


                    $sql = sprintf(
                        'INSERT INTO `Website Auth Token Dimension` (`Website Auth Token Website Key`,`Website Auth Token Selector`,`Website Auth Token Hash`,`Website Auth Token Website User Key`,`Website Auth Token Customer Key`,`Website Auth Token Website User Log Key`,`Website Auth Token Expire`) 
            VALUES (%d,%s,%s,%d,%d,%d,%s)', $store->get('Store Website Key'), prepare_mysql($selector), prepare_mysql(hash('sha256', $authenticator)), $website_user->id, $customer->id, $_SESSION['website_user_log_key'],
                        prepare_mysql(date('Y-m-d H:i:s', time() + 864000))

                    );

                    $db->exec($sql);


                } else {

                    echo json_encode(
                        array(
                            'state'  => 400,
                        )
                    );
                    exit;

                }

            }


            echo json_encode(
                array(
                    'state' => 200,
                    'msg'   => 'reg'
                )
            );
            exit;

        } else {
            echo json_encode(
                array(
                    'state' => 400,
                    'msg'   => $store->msg
                )
            );
            exit;
        }


    } else {
        echo json_encode(
            array(
                'state' => 400,
                'resp'  => 'Customer not found'
            )
        );
        exit;

    }


}




function get_clients($customer, $db) {


    $data = array();

    $sql = "SELECT `Customer Client Key`,`Customer Client Code`,`Customer Client Name`,`Customer Client Creation Date`,`Customer Client Location`
            FROM 
                `Customer Client Dimension` 
            WHERE   `Customer Client Customer Key`=?
            ";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id
        )
    );
    while ($row = $stmt->fetch()) {
        $data[] = array(
            $row['Customer Client Code'],
            $row['Customer Client Name'],
        );
    }


    echo json_encode(
        array('data' => $data)

    );

}


