<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 12:54:36 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';


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
    case 'category_products':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array(
                             'type'     => 'key',
                             'optional' => true
                         ),

                         'with_category_products'   => array(
                             'type'     => 'string',
                             'optional' => true
                         ),
                         'with_favourites_products' => array(
                             'type'     => 'string',
                             'optional' => true
                         ),


                     )
        );
        category_products($data, $db, $customer->id, $order);
        break;


    case 'out_of_stock_reminders':

        out_of_stock_reminders($db, $customer->id, $order);


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


function category_products($data, $db, $customer_key, $order) {


    $website = get_object('Website', $_SESSION['website_key']);


    $labels  = $website->get('Localised Labels');

    /*
      if (!$order->id) {
          $total = 0;
          $label = (isset($labels['_total']) ? $labels['_total'] : _('Total'));
          $items = 0;
      } else {
          if (!empty($website->settings['Info Bar Basket Amount Type']) and $website->settings['Info Bar Basket Amount Type'] == 'items_net') {
              $total = $order->get('Items Net Amount');
              $label = (isset($labels['_items_net']) ? $labels['_items_net'] : _('Items Net'));
              $items = $order->get('Products');

          } else {
              $total = $order->get('Items Net Amount');
              $label = (isset($labels['_total']) ? $labels['_total'] : _('Total'));
              $items = $order->get('Products');
          }
      }
  */

    $favourite              = array();
    $out_of_stock_reminders = array();
    $ordered_products       = array();
    $stock                  = array();


    $sql = sprintf(
        'SELECT `Customer Favourite Product Product ID`,`Customer Favourite Product Key` FROM `Customer Favourite Product Fact` WHERE `Customer Favourite Product Customer Key`=%d ', $customer_key
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $favourite[$row['Customer Favourite Product Product ID']] = $row['Customer Favourite Product Key'];
        }
    }


    $sql = sprintf(
        'SELECT `Back in Stock Reminder Product ID`,`Back in Stock Reminder Key` FROM `Back in Stock Reminder Fact` WHERE `Back in Stock Reminder Customer Key`=%d ', $customer_key
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $out_of_stock_reminders[$row['Back in Stock Reminder Product ID']] = $row['Back in Stock Reminder Key'];
        }
    }


    $sql = sprintf(
        'SELECT `Product ID`,`Order Quantity` FROM `Order Transaction Fact` WHERE `Order Key`=%d ', $order->id
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $ordered_products[$row['Product ID']] = $row['Order Quantity'];
        }
    }


    if (isset($data['with_category_products']) and $data['with_category_products'] == 'Yes') {
        $with_category_products = true;
    } else {
        $with_category_products = false;
    }

    if (isset($data['with_favourites_products']) and $data['with_favourites_products'] == 'Yes') {
        $with_favourites_products = true;
    } else {
        $with_favourites_products = false;
    }

    if ($with_favourites_products and ($website->settings('Display Stock Levels in Category') == 'Hint_Bar' or $website->settings('Display Stock Levels in Category') == 'Dot')) {

        $show_stock_value = $website->settings('Display Stock Quantity');
        if ($show_stock_value == '') {
            $show_stock_value = 'No';
        }


        $sql =
            "SELECT `Product Availability State`,`Product Availability`,`Product ID` FROM `Customer Favourite Product Fact`    left join `Product Dimension` P on (P.`Product ID`=`Customer Favourite Product Product ID`)  WHERE `Customer Favourite Product Customer Key`=? ";

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array($customer_key)
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
                $stock_label
            );

        }
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
            'state'                  => 200,
            'favourite'              => $favourite,
            'out_of_stock_reminders' => $out_of_stock_reminders,

            'ordered_products' => $ordered_products,
            //'total'            => $total,
            //'items'            => $items,
            //'label'            => $label,
            'stock'            => $stock,

        )
    );
    exit;


}



