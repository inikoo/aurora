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

$account=get_object('Account',1);

switch ($tipo) {

    case 'new_customer_client':
        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),
                     )
        );
        new_customer_client($data, $customer, $account,$editor );
        break;
    case 'get_clients':
        get_clients($customer, $db);
        break;

        break;

}

/**
 * @param $data
 * @param $customer \Public_Customer
 * @param $account  \Public_Account
 * @param $editor
 *
 * @throws \Exception
 */
function new_customer_client($data, $customer, $account, $editor) {

    include_once 'utils/new_fork.php';


    $customer->editor = $editor;
    $raw_data         = $data['data'];


    if ($customer->id) {
        $customer_data = array(
            'Customer Client Code'              => $raw_data['client_reference'],
            'Customer Client Main Contact Name' => $raw_data['name'],
            'Customer Client Company Name'      => $raw_data['organization'],
            'Customer Client Main Plain Email'  => $raw_data['email'],
            'Customer Client Main Plain Mobile' => $raw_data['tel'],

        );

        if (array_key_exists('locality', $raw_data)) {
            $customer_data['Customer Client Contact Address locality'] = $raw_data['locality'];
        }
        if (array_key_exists('postalCode', $raw_data)) {
            $customer_data['Customer Client Contact Address postalCode'] = $raw_data['postalCode'];
        }
        if (array_key_exists('addressLine1', $raw_data)) {
            $customer_data['Customer Client Contact Address addressLine1'] = $raw_data['addressLine1'];
        }
        if (array_key_exists('addressLine2', $raw_data)) {
            $customer_data['Customer Client Contact Address addressLine2'] = $raw_data['addressLine2'];
        }
        if (array_key_exists('administrativeArea', $raw_data)) {
            $customer_data['Customer Client Contact Address administrativeArea'] = $raw_data['administrativeArea'];
        }
        if (array_key_exists('dependentLocality', $raw_data)) {
            $customer_data['Customer Client Contact Address dependentLocality'] = $raw_data['dependentLocality'];
        }
        if (array_key_exists('sortingCode', $raw_data)) {
            $customer_data['Customer Client Contact Address sortingCode'] = $raw_data['sortingCode'];
        }

        if (array_key_exists('country', $raw_data)) {
            $customer_data['Customer Client Contact Address country'] = $raw_data['country'];
        }




        $client = $customer->create_client($customer_data);



        if ($customer->new_client) {


            include_once 'utils/new_fork.php';


            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'                => 'customer_client_created',
                'customer_key'        => $customer->id,
                'customer_client_key' => $client->id,

                'editor' => $editor
            ), $account->get('Account Code')
            );


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
                    'msg'   => $customer->msg
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

    $sql = "SELECT `Customer Client Key`,`Customer Client Code`,`Customer Client Name`,`Customer Client Creation Date`,`Customer Client Location`,`Customer Client Orders`
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
        $action='<button onclick="window.location.href = \'client_basket.sys?client_id='.$row['Customer Client Key'].'\';">'._('New order').'</button>';

        $data[] = array(
            sprintf('<a href="client.sys?id=%d">%s</a>',$row['Customer Client Key'],$row['Customer Client Code']),
            $row['Customer Client Name'],
            $row['Customer Client Orders'],
            $action
        );
    }


    echo json_encode(
        array('data' => $data)

    );

}


