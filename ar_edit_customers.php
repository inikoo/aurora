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
        'msg'   => 'Non acceptable request (t)'
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
    case 'set_up_integration':
        $data = prepare_values(
            $_REQUEST, array(
                         'customer_key'     => array('type' => 'key'),
                         'integration_type' => array('type' => 'string'),
                     )
        );
        switch ($data['integration_type']) {
            case 'Shopify':
                set_up_shopify($data, $db, $user);
                break;
            default:
                $response = array(
                    'state' => 405,
                    'msg'   => 'Integration not found '.$data['integration_type']
                );
                echo json_encode($response);
                exit;
        }


        break;
    default:
        $response = array(
            'state' => 405,
            'msg'   => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;

}

function set_up_shopify($data, $db, $user) {


    $customer = get_object('Customer', $data['customer_key']);
    $store    = get_object('Store', $customer->get('Store Key'));


    if ($store->get('Store Shopify API Key') == '' or !defined('SHOPIFY_URL')) {
        $response = array(
            'state' => 400,
            'msg'   => 'Shopify integration not set up'
        );
        echo json_encode($response);
        exit;
    }


    $params = [
        'id'   => $customer->id,
        'data' => '{}'
    ];


    $url = SHOPIFY_URL.'/api/register/';


    $curl = curl_init();

    curl_setopt_array(
        $curl, array(
                 CURLOPT_URL            => $url."?".http_build_query($params),
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_ENCODING       => "",
                 CURLOPT_MAXREDIRS      => 10,
                 CURLOPT_TIMEOUT        => 0,
                 CURLOPT_FOLLOWLOCATION => true,
                 CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                 CURLOPT_CUSTOMREQUEST  => "POST",
                 CURLOPT_HTTPHEADER     => array(
                     "Accept: application/json",
                     "Authorization: Bearer ".$store->get('Store Shopify API Key')
                 ),
             )
    );

    $response = curl_exec($curl);

    curl_close($curl);
    //echo "Params:\n".print_r($params)." <<==\n";
    //echo "Response:".$response.' <<';
    if ($response) {
        $res = json_decode($response, true);


        $response = array(
            'state'  => 200,
            'result' => $res['accessCode'],
            'update_metadata'   => [

            ]
        );
        echo json_encode($response);
        exit;

    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'Error try again later'
        );
        echo json_encode($response);
        exit;
    }


    return 1;

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
            'msg'   => 'Forbidden'
        );
        echo json_encode($response);
        exit;
    }

    $product = get_object('Product', $data['product_id']);

    if ($product->get('Store Key') != $customer->get('Store Key')) {
        $response = array(
            'state' => 400,
            'msg'   => 'Product not in Store'
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
            'msg'   => _('Product already in portfolio')
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
            'msg'   => 'Forbidden'
        );
        echo json_encode($response);
        exit;
    }

    $product = get_object('Product', $data['product_id']);

    if ($product->get('Store Key') != $customer->get('Store Key')) {
        $response = array(
            'state' => 400,
            'msg'   => 'Product not in Store'
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
            'msg'   => 'Product not in portfolio'
        );
        echo json_encode($response);
        exit;
    }


}