<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 330 June 2017 at 18:24:32 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_web_common.php';
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
switch ($tipo) {
    case 'validate_email_registered':
        $data = prepare_values(
            $_REQUEST, array(
                         'email'       => array('type' => 'string'),
                         'website_key' => array('type' => 'key')
                     )
        );
        validate_email_registered($db, $data);
        break;

    case 'validate_update_email':
        $data = prepare_values(
            $_REQUEST, array(
                         'email' => array('type' => 'string')
                     )
        );
        validate_update_email($db, $data, $website_key, $customer);
        break;
    case 'validate_object_reference':
        $data = prepare_values(
            $_REQUEST, array(
                         'reference'  => array('type' => 'string'),
                         'object'     => array('type' => 'string'),
                         'object_key' => array('type' => 'key'),
                     )
        );

        if ($data['object'] == 'Portfolio_Item') {
            validate_portfolio_reference($data, $db, $customer);

        } elseif ($data['object'] == 'Client') {
            validate_client_reference($data, $db, $customer);

        }else{
            $response = array(
                'state' => 405,
                'resp'  => 'validate_object_reference not found '.$data['object']
            );
            echo json_encode($response);
            exit;
        }
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}


function validate_update_email($db, $data, $website_key, $customer) {


    $sql = "SELECT `Website User Key` from `Website User Dimension` WHERE  `Website User Handle`=? AND `Website User Website Key`=?  and `Website User Key`!=? ";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $data['email'],
            $website_key,
            $customer->get('Customer Website User Key')
        )
    );
    if ($row = $stmt->fetch()) {
        echo "false";
    } else {
        echo "true";
    }


}

function validate_email_registered($db, $data) {


    $sql = sprintf(
        "SELECT `Website User Key` from `Website User Dimension` WHERE  `Website User Handle`=%s AND `Website User Website Key`=%d", prepare_mysql($data['email']), $data['website_key']

    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            echo "false";
        } else {
            echo "true";
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}

/**
 * @param $data
 * @param $db \PDO
 * @param $customer \Public_Customer
 */
function validate_portfolio_reference($data, $db, $customer) {
    if ($data['reference'] == '') {
        echo json_encode(
            array(
                'state' => 200,
                'ok'    => true,


            )
        );
        exit;
    }

    $sql  = "select `Customer Portfolio Key` from `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=? and `Customer Portfolio Key`!=? and `Customer Portfolio Reference`=?  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id,
            $data['object_key'],
            $data['reference']
        )
    );
    if ($row = $stmt->fetch()) {
        echo json_encode(
            array(
                'state' => 200,
                'ok'    => false,


            )
        );

    } else {
        echo json_encode(
            array(
                'state' => 200,
                'ok'    => true,


            )
        );

    }


}

/**
 * @param $data
 * @param $db \PDO
 * @param $customer \Public_Customer
 */
function validate_client_reference($data, $db, $customer) {

    if ($data['reference'] == '') {
        echo json_encode(
            array(
                'state' => 200,
                'ok'    => true,


            )
        );
        exit;
    }

    $sql  = "select `Customer Client Key` from `Customer Client Dimension` where `Customer Client Customer Key`=? and `Customer Client Key`!=? and `Customer Client Code`=?  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id,
            $data['object_key'],
            $data['reference']
        )
    );

    if ($row = $stmt->fetch()) {

        echo json_encode(
            array(
                'state' => 200,
                'ok'    => false,


            )
        );

    } else {
        echo json_encode(
            array(
                'state' => 200,
                'ok'    => true,


            )
        );

    }


}