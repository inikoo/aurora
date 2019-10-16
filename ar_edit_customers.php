<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Wed 16 Oct 2019 15:53:39 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


require 'vendor/autoload.php';

require_once 'common.php';
require_once 'utils/ar_common.php';

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'msg'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];


switch ($tipo) {


    case 'add_product_to_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'customer_key' => array('type' => 'key'),
                         'product_id'   => array('type' => 'key'),
                     )
        );
        add_product_to_portfolio($data, $db, $user);
        break;
    case 'remove_product_from_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'customer_key' => array('type' => 'key'),
                         'product_id'   => array('type' => 'key'),
                     )
        );
        remove_product_from_portfolio($data, $db, $user);
        break;
    default:
        $response = array(
            'state' => 405,
            'msg'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}

/**
 * @param $data array
 * @param $db   \PDO
 * @param $user \User
 */
function add_product_to_portfolio($data, $db, $user) {

    /**
     * @var $customer \Customer
     */
    $customer = get_object('Customer', $data['customer_key']);
    if (!($user->can_edit('customers') and in_array($customer->get('Store Key'), $user->stores))) {
        $response = array(
            'state' => 400,
            'msg'  => 'Forbidden'
        );
        echo json_encode($response);
        exit;
    }

    $product = get_object('Product', $data['product_id']);

    if ($product->get('Store Key') != $customer->get('Store Key')) {
        $response = array(
            'state' => 400,
            'msg'  => 'Product not in Store'
        );
        echo json_encode($response);
        exit;

    }


    $sql  = "select `Customer Portfolio Key` from  `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=? and `Customer Portfolio Product ID`=? and `Customer Portfolio Customers State`='Active'";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id,
            $product->id,
        )
    );
    if ($row = $stmt->fetch()) {
        $response = array(
            'state' => 400,
            'msg'  => _('Product already in portfolio')
        );
        echo json_encode($response);
        exit;
    } else {
        $date = gmdate('Y-m-d H:i:s');

        $sql  =
            "INSERT INTO `Customer Portfolio Fact` (`Customer Portfolio Store Key`,`Customer Portfolio Customer Key`,`Customer Portfolio Product ID`,`Customer Portfolio Creation Date`) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE `Customer Portfolio Customers State`='Active'";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $customer->get('Store Key'),
                $customer->id,
                $product->id,
                $date

            )
        );
        $customer_portfolio_key = $db->lastInsertId();
        $sql                    = "INSERT INTO `Customer Portfolio Timeline` (`Customer Portfolio Timeline Customer Portfolio Key`,`Customer Portfolio Timeline Action`,`Customer Portfolio Timeline Date`) VALUES (?,?,?)";
        $stmt                   = $db->prepare($sql);
        $stmt->execute(
            array(
                $customer_portfolio_key,
                'Add',
                $date

            )
        );
        $customer->update_portfolio();

        $response = array(
            'state'           => 200,
            'update_metadata' => array(
                'class_html' => array(
                    'Number_Products_in_Portfolio' => '('.$customer->get('Number Products in Portfolio').')'

                )
            )
        );
        echo json_encode($response);
        exit;
    }


}


/**
 * @param $data array
 * @param $db   \PDO
 * @param $user \User
 */
function remove_product_from_portfolio($data, $db, $user) {

    /**
     * @var $customer \Customer
     */
    $customer = get_object('Customer', $data['customer_key']);
    if (!($user->can_edit('customers') and in_array($customer->get('Store Key'), $user->stores))) {
        $response = array(
            'state' => 400,
            'msg'  => 'Forbidden'
        );
        echo json_encode($response);
        exit;
    }

    $product = get_object('Product', $data['product_id']);

    if ($product->get('Store Key') != $customer->get('Store Key')) {
        $response = array(
            'state' => 400,
            'msg'  => 'Product not in Store'
        );
        echo json_encode($response);
        exit;

    }


    $sql  = "select `Customer Portfolio Key` from  `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=? and `Customer Portfolio Product ID`=? and `Customer Portfolio Customers State`='Active'";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id,
            $product->id,
        )
    );
    if ($row = $stmt->fetch()) {
        $date = gmdate('Y-m-d H:i:s');

        $sql  = "update `Customer Portfolio Fact`  set  `Customer Portfolio Customers State`='Removed', `Customer Portfolio Removed Date`=? where `Customer Portfolio Key`=?";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(

                $date,
                $row['Customer Portfolio Key'],

            )
        );
        $sql  = "INSERT INTO `Customer Portfolio Timeline` (`Customer Portfolio Timeline Customer Portfolio Key`,`Customer Portfolio Timeline Action`,`Customer Portfolio Timeline Date`) VALUES (?,?,?)";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $row['Customer Portfolio Key'],
                'Remove',
                $date

            )
        );
        $customer->update_portfolio();

        $response = array(
            'state'           => 200,
            'update_metadata' => array(
                'class_html' => array(
                    'Number_Products_in_Portfolio' => '('.$customer->get('Number Products in Portfolio').')'

                )
            )
        );
        echo json_encode($response);
        exit;
    } else {
        $response = array(
            'state' => 400,
            'msg'  => 'Product not in portfolio'
        );
        echo json_encode($response);
        exit;
    }


}