<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Sat 26 Oct 2019 01:32:00 +0800 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
require_once 'utils/table_functions.php';

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];

$account = get_object('Account', 1);

switch ($tipo) {

    case 'new_customer_client':
    case 'order_for_new_customer':

        $data = prepare_values(
            $_REQUEST, array(
                         'data' => array('type' => 'json array'),
                     )
        );
        new_customer_client($data, $customer, $account, $editor);
        break;

    case 'customer_clients':
        $_data                             = get_table_parameters();
        $_data['parameters']['parent_key'] = $customer->id;
        customer_clients($_data, $db);


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
                    'state'     => 200,
                    'msg'       => 'reg',
                    'client_id' => $client->id,
                    'metadata'  => [
                        'class_html' => []
                    ]
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


function customer_clients($_data, $db) {


    include_once 'utils/currency_functions.php';

    $rtext_label = 'customer';


    include_once 'prepare_table/init.php';
    /**
     * @var string $fields
     * @var string $table
     * @var string $where
     * @var string $wheref
     * @var string $group_by
     * @var string $order
     * @var string $order_direction
     * @var string $start_from
     * @var string $number_results
     * @var string $rtext
     * @var string $_order
     * @var string $_dir
     * @var string $total
     */

    $sql         = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";
    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {
            $action = '<span class="like_button" onclick="window.location.href = \'/client_basket.sys?client_id='.$data['Customer Client Key'].'\';"><i class="far fa-shopping-cart padding_right_10"></i>   '._('New order').'</span>';

            $record_data[] = array(
                'id'   => (integer)$data['Customer Client Key'],
                'code' => sprintf('<a href="client.sys?id=%d">%s</a>', $data['Customer Client Key'], $data['Customer Client Code']),
                'name' => $data['Customer Client Name'],

                'since'          => strftime("%e %b %y", strtotime($data['Customer Client Creation Date'].' +0:00')),
                'location'       => $data['Customer Client Location'],
                'pending_orders' => number($data['Customer Client Pending Orders']),
                'invoices'       => number($data['Customer Client Number Invoices']),
                'last_invoice'   => ($data['Customer Client Last Invoice Date'] == '' ? '' : strftime("%e %b %y", strtotime($data['Customer Client Last Invoice Date'].' +0:00'))),

                'total_invoiced_amount' => money($data['Customer Client Invoiced Amount'], $data['Customer Client Currency Code']),
                'address'               => $data['Customer Client Contact Address Formatted'],
                'email'                 => $data['Customer Client Main Plain Email'],
                'telephone'             => $data['Customer Client Main XHTML Telephone'],
                'mobile'                => $data['Customer Client Main XHTML Mobile'],
                'operations'            => $action
            );
        }


        $response = array(
            'resultset' => array(
                'state'         => 200,
                'data'          => $record_data,
                'rtext'         => $rtext,
                'sort_key'      => $_order,
                'sort_dir'      => $_dir,
                'total_records' => $total

            )
        );
        echo json_encode($response);

    }
}


