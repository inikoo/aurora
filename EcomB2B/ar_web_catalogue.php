<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Fri 25 Oct 2019 22:57:21 +0800 MYT Kuala Lumpur, Malaysia
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


$account = get_object('Account', 1);

$tipo = $_REQUEST['tipo'];

switch ($tipo) {



    case 'products':
        $_data                             = get_table_parameters();
        $_data['parameters']['parent_key'] = $customer->id;
        portfolio_items($_data, $db);

        break;


        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
}






/**
 * @param $data
 * @param $db \PDO
 * @param $customer_key
 */
function products($data, $db, $customer_key) {


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
            "select `Product Availability State`,`Product Availability`,`Product ID`,`Website Webpage Scope Type` from `Website Webpage Scope Map`  left join `Product Dimension` P on (P.`Product ID`=`Website Webpage Scope Scope Key`) where `Website Webpage Scope Scope`='Product' and `Website Webpage Scope Webpage Key`=? ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array($data['webpage_key'])
        );


        while ($row = $stmt->fetch()) {


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
                $stock_label,
                $row['Website Webpage Scope Type']
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


