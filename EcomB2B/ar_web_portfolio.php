<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Fri 25 Oct 2019 22:57:21 +0800 MYT Kuala Lumpur, Malaysia
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


$account = get_object('Account', 1);

$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'category_products':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key'            => array(
                             'type' => 'key',
                         ),
                         'with_category_products' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );
        category_products($data, $db, $customer->id);
        break;

    case 'get_portfolio_items':
        get_portfolio_items($customer, $db);
        break;
    case 'add_product_to_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'product_id'  => array('type' => 'key'),
                         'webpage_key' => array('type' => 'key')
                     )
        );

        add_product_to_portfolio($data, $db, $customer, $account);

        break;

    case 'remove_product_from_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'product_id'  => array('type' => 'key'),
                         'webpage_key' => array('type' => 'key'),
                     )
        );

        remove_product_from_portfolio($data, $db, $customer, $account);
        break;


}

function get_portfolio_items($customer, $db) {

    $portfolio_items = array();


    $data = array();

    $sql = "SELECT `Product ID`,`Product Code`,`Product Name`,`Product Web State`,`Customer Portfolio Key`,`Product Units Per Case`
            FROM 
                `Customer Portfolio Fact` CPF left join  `Product Dimension` P  on (`Customer Portfolio Product ID`=P.`Product ID`) 
            WHERE   `Customer Portfolio Customer Key`=?
            ";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id
        )
    );
    while ($row = $stmt->fetch()) {
        $data[] = array(
            $row['Product Code'],
            ($row['Product Units Per Case'] > 1 ? $row['Product Units Per Case'].'x' : '').$row['Product Name']
        );
    }


    echo json_encode(
        array('data' => $data)

    );

}


function category_products($data, $db, $customer_key) {


    $website = get_object('Website', $_SESSION['website_key']);
    $labels  = $website->get('Localised Labels');


    $products_in_portfolio = array();
    $stock                 = array();


    $sql =
        "SELECT `Customer Portfolio Product ID` FROM `Customer Portfolio Fact`  left join `Website Webpage Scope Map` on (`Customer Portfolio Product ID`=`Website Webpage Scope Scope Key`)  WHERE  `Website Webpage Scope Scope`='Product' and   `Customer Portfolio Customers State`='Active'     and `Customer Portfolio Customer Key`=? and `Website Webpage Scope Webpage Key`=?";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer_key,
            $data['webpage_key']
        )
    );
    while ($row = $stmt->fetch()) {
        $products_in_portfolio[$row['Customer Portfolio Product ID']] = true;

    }


    if (isset($data['with_category_products']) and $data['with_category_products'] == 'Yes') {
        $with_category_products = true;
    } else {
        $with_category_products = false;
    }


    if (($with_category_products and ($website->settings('Display Stock Levels in Category') == 'Hint_Bar' or $website->settings('Display Stock Levels in Category') == 'Dot')) or (!$with_category_products and $website->settings('Display Stock Levels in Product')
            == 'Yes')) {

        $show_stock_value = $website->settings('Display Stock Quantity');
        if ($show_stock_value == '') {
            $show_stock_value = 'No';
        }

        $sql  =
            "select `Product Availability State`,`Product Availability`,`Product ID` from `Website Webpage Scope Map`  left join `Product Dimension` P on (P.`Product ID`=`Website Webpage Scope Scope Key`) where `Website Webpage Scope Scope`='Product' and `Website Webpage Scope Webpage Key`=? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array($data['webpage_key'])
        );
        while ($row = $stmt->fetch()) {
            //'Excess','Normal','Low','VeryLow','OutofStock','Error','OnDemand'

            switch ($row['Product Availability State']) {
                case 'OnDemand':
                    $stock_label = (!empty($labels['_stock_OnDemand']) ? $labels['_stock_OnDemand'] : _('Product made on demand'));
                    break;
                case 'Excess':
                    $stock_label = (!empty($labels['_stock_Excess']) ? $labels['_stock_Excess'] : _('Plenty of stock'));
                    if ($show_stock_value == 'Yes') {
                        $stock_label .= ' ('.number($row['Product Availability']).')';
                    }
                    break;
                case 'Normal':
                    $stock_label = (!empty($labels['_stock_Normal']) ? $labels['_stock_Normal'] : _('Plenty of stock'));
                    if ($show_stock_value == 'Yes') {
                        $stock_label .= ' ('.number($row['Product Availability']).')';
                    }
                    break;
                case 'Low':
                    $stock_label = (!empty($labels['_stock_Low']) ? $labels['_stock_Low'] : _('Limited stock'));
                    if ($show_stock_value == 'Yes') {
                        $stock_label .= ' ('.number($row['Product Availability']).')';
                    }
                    break;
                case 'VeryLow':
                    $stock_label = (!empty($labels['_stock_OutofStock']) ? $labels['_stock_OutofStock'] : _('Very low stock'));
                    if ($show_stock_value == 'Yes' or $show_stock_value == 'Only_if_very_low') {
                        $stock_label .= ' ('.number($row['Product Availability']).')';
                    }
                    break;

                case 'OutofStock':
                case 'Error':

                    $stock_label = (!empty($labels['_stock_OutofStock']) ? $labels['_stock_OutofStock'] : _('Out of stock'));
                    break;
                default:
                    $stock_label = $row['Product Availability State'];
            }


            $stock[$row['Product ID']] = array(
                $row['Product Availability State'],
                $stock_label
            );

        }
    }


    echo json_encode(
        array(
            'state'                 => 200,
            'products_in_portfolio' => $products_in_portfolio,
            'stock'                 => $stock,

        )
    );
    exit;


}

/**
 * @param $data     array
 * @param $db       \PDO
 * @param $customer \Public_Customer
 * @param $account  \Public_Account
 */
function add_product_to_portfolio($data, $db, $customer, $account) {

    include_once 'utils/new_fork.php';

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


        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'         => 'customer_portfolio_changed',
            'customer_key' => $customer->id,
        ), $account->get('Account Code')
        );

        $response = array(
            'state'  => 200,
            'result' => 'add'
        );
        echo json_encode($response);
        exit;
    }


}


/**
 * @param $data     array
 * @param $db       \PDO
 * @param $customer \Public_Customer
 * @param $account  \Public_Account
 */
function remove_product_from_portfolio($data, $db, $customer, $account) {

    include_once 'utils/new_fork.php';

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

        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'         => 'customer_portfolio_changed',
            'customer_key' => $customer->id,
        ), $account->get('Account Code')
        );


        $response = array(
            'state'  => 200,
            'result' => 'remove'
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



