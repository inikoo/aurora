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
include_once 'utils/shopify_connect.php';
require_once 'utils/aiku_stand_alone_process_aiku_fetch.php';

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


    case 'portfolio_items':
        $_data                             = get_table_parameters();
        $_data['parameters']['parent_key'] = $customer->id;
        portfolio_items($_data, $db);

        break;
    case 'add_product_to_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'product_id' => array('type' => 'key'),
                     )
        );

        add_product_to_portfolio($data, $db, $customer);

        break;
    case 'add_category_to_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'webpage_key' => array('type' => 'key'),
                     )
        );

        add_category_to_portfolio($data, $db, $customer, $account);

        break;
    case 'remove_product_from_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'product_id' => array('type' => 'key'),
                     )
        );

        remove_product_from_portfolio($data, $db, $customer, $account);
        break;

    case 'update_portfolio_product_reference':

        $data = prepare_values(
            $_REQUEST, array(
                         'reference'              => array('type' => 'string'),
                         'customer_portfolio_key' => array('type' => 'key'),
                     )
        );

        update_portfolio_product_reference($data, $db, $customer);

        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
}

function update_portfolio_product_reference($data, $db, $customer) {

    $reference = trim($data['reference']);


    if ($reference != '') {
        $sql  = "select `Customer Portfolio Key` from `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=? and `Customer Portfolio Key`!=? and `Customer Portfolio Reference`=?  ";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            array(
                $customer->id,
                $data['customer_portfolio_key'],
                $data['reference']
            )
        );
        if ($row = $stmt->fetch()) {
            echo json_encode(
                array(
                    'state'     => 200,
                    'ok'        => false,
                    'reference' => $reference

                )
            );
            exit;
        }
    }

    $sql = "update `Customer Portfolio Fact` set `Customer Portfolio Reference`=?  where `Customer Portfolio Customer Key`=? and `Customer Portfolio Key`=?   ";
    $db->prepare($sql)->execute(
        array(
            ($reference == '' ? null : $reference),
            $customer->id,
            $data['customer_portfolio_key'],
        )
    );

    $store= get_object('Store',$customer->get('Store Key'));
    shopify_update_portfolio_item($store,$data['customer_portfolio_key'],['product_code'=>$reference]);


    stand_alone_process_aiku_fetch(
        $db,
        'Portfolio',
        $data['customer_portfolio_key']
    );
    echo json_encode(
        array(
            'state'               => 200,
            'ok'                  => true,
            'reference'           => $reference,
            'formatted_reference' => ($reference == '' ? _('Add reference') : $reference)

        )
    );
    exit;


}

/**
 * @param $_data
 * @param $db \PDO
 */
function portfolio_items($_data, $db) {


    include_once 'utils/currency_functions.php';

    $rtext_label = 'product';


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

    $sql = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

    $record_data = array();
    if ($result = $db->query($sql)) {

        foreach ($result as $data) {

            switch ($data['Product Availability State']) {
                case 'Excess':
                case 'Normal':


                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#13D13D" title="%s"></i>', _('Active'));
                    break;
                case 'OnDemand':
                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#13D13D" title="%s"></i>', _('On demand'));
                    break;
                case 'Error':
                case 'OutofStock':
                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#F25056" title="%s"></i>', _('Out of stock'));
                    break;
                case 'VeryLow':
                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#ff7c00" title="%s"></i>', _('Very low stock'));
                    break;
                case 'Low':
                    $stock_status = sprintf('<i class="fa fa-circle " style="color:#FCBE07" title="%s"></i>', _('Low stock'));
                    break;

                default:
                    $stock_status = $data['Product Availability State'];
                    break;
            }

            if ($data['Product Status'] == 'Discontinued') {
                $stock_status = '';
                $status_icon  = ' <i class="fa fa-skull discontinued" title="'._('Discontinued').'"></i>';

            } elseif ($data['Product Status'] == 'Discontinuing') {
                $status_icon = ' <i class="far discontinuing  fa-skull" title="'._('Discontinuing').'"></i>';
            } else {
                $status_icon = '';
            }


            $name = '<span >'.$data['Product Units Per Case'].'</span>x <span>'.$data['Product Name'].'</span>';

            if ($data['Webpage URL'] == '') {
                $code = sprintf('<span  class="%s"  title="%s">%s</span>', ($data['Product Status'] == 'Discontinued' ? 'strikethrough' : ''), $name, $data['Product Code']);

            } else {
                $code = sprintf('<a class="link %s " style="display: inline" href="%s" title="%s">%s</a>', ($data['Product Status'] == 'Discontinued' ? 'strikethrough' : ''), $data['Webpage URL'], $name, $data['Product Code']);

            }

            if ($data['Customer Portfolio Reference'] == '') {
                $reference        = sprintf(
                    '<span id="portfolio_ref_%d" class="table_inline_edit edit_object_reference_container  " data-object="Portfolio_Item"  data-object_key="%d"><span class="very_discreet italic like_button edit_object_reference">%s</span> <span class="editor hide">
<input class="" data-old_value="" />  <i class="fa fa-fw fa-cloud save "></i> </span></span>  ', $data['Customer Portfolio Key'], $data['Customer Portfolio Key'], _('Add reference')
                );
                $reference_mobile = '';
            } else {
                $reference        = sprintf(
                    '<span id="portfolio_ref_%d" class="table_inline_edit edit_object_reference_container  "  data-object="Portfolio_Item"  data-object_key="%d"><span class="  like_button edit_object_reference">%s</span> <span class="editor hide">
<input class="" data-old_value="%s" value="%s"/>  <i class="fa fa-fw fa-cloud save "></i> </span></span>  ', $data['Customer Portfolio Key'], $data['Customer Portfolio Key'], $data['Customer Portfolio Reference'], $data['Customer Portfolio Reference'],
                    $data['Customer Portfolio Reference']
                );
                $reference_mobile = ' ('.$data['Customer Portfolio Reference'].')';
            }


            if ($data['Image Key'] != 0) {
                $image = sprintf(
                    '<img src="/rwi/50x50_%d.%s" style="display: block;  max-width:50px; max-height:50px; width: auto; height: auto;">', $data['Image Key'], $data['Image File Format']
                );
            } else {
                $image = 'x';
            }


            $record_data[] = array(

                'id'           => (integer)$data['Product ID'],
                'code'         => $code.$status_icon,
                'name'         => $name,
                'reference'    => $reference,
                'stock_status' => $stock_status.$status_icon,
                'image'        => $image,
                'description'  => $code.$status_icon.' '.$reference_mobile.'<br>'.$name.' <b>'.money($data['Product Price'], $data['Store Currency Code']).'</b>',
                'price'        => money($data['Product Price'], $data['Store Currency Code']),
                'rrp'          => money($data['Product RRP'], $data['Store Currency Code']),
                'last_order'   => ($data['Customer Portfolio Last Ordered'] == '' ? '' : strftime("%a %e %b %Y", strtotime($data['Customer Portfolio Last Ordered'].' +0:00'))),
                'amount'       => sprintf('<span>%s</span>', money($data['Customer Portfolio Amount'], $data['Store Currency Code'])),
                'qty'          => sprintf('<span>%s</span>', number($data['Customer Portfolio Ordered Quantity'])),
                'orders'       => sprintf('<span>%s</span>', number($data['Customer Portfolio Orders'])),
                'clients'      => sprintf('<span>%s</span>', number($data['Customer Portfolio Clients'])),
                'operations'   => '<i class="far like_button fa-trash-alt" onclick="remove_item_from_portfolio(this,'.$data['Customer Portfolio Customer Key'].','.$data['Product ID'].')" ></i>'

            );


        }

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


/**
 * @param $data
 * @param $db \PDO
 * @param $customer_key
 */
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

/**
 * @param $data     array
 * @param $db       \PDO
 * @param $customer \Public_Customer
 */
function add_product_to_portfolio($data, $db, $customer) {

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

    $store = get_object('Store', $product->get('Store Key'));


    $sql  = "select `Customer Portfolio Key`,`Customer Portfolio Customers State` from  `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=? and `Customer Portfolio Product ID`=?";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id,
            $product->id,
        )
    );
    if ($row = $stmt->fetch()) {


        if ($row['Customer Portfolio Customers State'] == 'Active') {

            $response = array(
                'state'           => 200,
                'result'          => 'no_change',
                'msg'             => _('Product already in portfolio'),
                'update_metadata' => [
                    'class_html' => []
                ]
            );
            echo json_encode($response);
            exit;
        } else {
            $date = gmdate('Y-m-d H:i:s');
            $sql  = "update  `Customer Portfolio Fact` set `Customer Portfolio Customers State`='Active'  where  `Customer Portfolio Key`=? ";
            $db->prepare($sql)->execute(
                array(
                    $row['Customer Portfolio Key']
                )
            );

            $sql  = "INSERT INTO `Customer Portfolio Timeline` (`Customer Portfolio Timeline Customer Portfolio Key`,`Customer Portfolio Timeline Action`,`Customer Portfolio Timeline Date`) VALUES (?,?,?)";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $row['Customer Portfolio Key'],
                    'Add',
                    $date

                )
            );


            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'         => 'customer_portfolio_changed',
                'customer_key' => $customer->id,
                'product_id'   => $product->id
            ), DNS_ACCOUNT_CODE
            );


            $response = array(
                'state'           => 200,
                'result'          => 'add',
                'update_metadata' => [
                    'class_html' => []
                ]
            );

            stand_alone_process_aiku_fetch(
                $db,
                'Portfolio',
                $row['Customer Portfolio Key'],
            );

            echo json_encode($response);
            exit;

        }


    }
    else {
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


        shopify_create_portfolio_item($store,$customer->id, $customer_portfolio_key);


        if ($customer_portfolio_key) {
            $sql  = "INSERT INTO `Customer Portfolio Timeline` (`Customer Portfolio Timeline Customer Portfolio Key`,`Customer Portfolio Timeline Action`,`Customer Portfolio Timeline Date`) VALUES (?,?,?)";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $customer_portfolio_key,
                    'Add',
                    $date

                )
            );

            stand_alone_process_aiku_fetch(
                $db,
                'Portfolio',
                $customer_portfolio_key,
            );


            new_housekeeping_fork(
                'au_housekeeping', array(
                'type'         => 'customer_portfolio_changed',
                'customer_key' => $customer->id,
                'product_id'   => $product->id
            ), DNS_ACCOUNT_CODE
            );
        }


        $response = array(
            'state'           => 200,
            'result'          => 'add',
            'update_metadata' => [
                'class_html' => []
            ]
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
    $store = get_object('Store', $customer->get('Store Key'));


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

        shopify_delete_portfolio_item($store,$row['Customer Portfolio Key']);


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
            'product_id'   => $product->id

        ), $account->get('Account Code')
        );

        stand_alone_process_aiku_fetch(
            $db,
            'Portfolio',
            $row['Customer Portfolio Key']
        );


        $response = array(
            'state'           => 200,
            'result'          => 'remove',
            'update_metadata' => [
                'class_html' => []
            ]
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

/**
 * @param $data     array
 * @param $db       \PDO
 * @param $customer \Public_Customer
 * @param $account  \Public_Account
 */
function add_category_to_portfolio($data, $db, $customer, $account) {

    include_once 'utils/new_fork.php';

    $webpage = get_object('Webpage', $data['webpage_key']);

    if ($webpage->get('Webpage Store Key') != $customer->get('Store Key')) {
        $response = array(
            'state' => 400,
            'msg'   => 'Webpage not in store'
        );
        echo json_encode($response);
        exit;

    }

    $store=get_object('Store',$customer->get('Store Key'));

    $sql  = "select `Website Webpage Scope Scope Key` as  product_id  from `Website Webpage Scope Map` where  `Website Webpage Scope Type`='Category_Products_Item' and  `Website Webpage Scope Scope`='Product' and `Website Webpage Scope Webpage Key`=?  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $webpage->id
        )
    );

    $products = [];
    while ($row = $stmt->fetch()) {
        $products[$row['product_id']] = $row['product_id'];
    }

    $sql  =
        "select `Customer Portfolio Product ID` as  product_id  from `Website Webpage Scope Map` left join `Customer Portfolio Fact` on (`Customer Portfolio Product ID`=`Website Webpage Scope Scope Key`) where  `Website Webpage Scope Type`='Category_Products_Item' and  `Website Webpage Scope Scope`='Product'  and `Customer Portfolio Customers State`='Active' and `Website Webpage Scope Webpage Key`=?  and `Customer Portfolio Customer Key`=? ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(

            $webpage->id,
            $customer->id
        )
    );


    while ($row = $stmt->fetch()) {
        unset($products[$row['product_id']]);
    }


    $number_added_items_to_portfolio = count($products);

    foreach ($products as $product_id) {
        $date = gmdate('Y-m-d H:i:s');


        $sql   = "select `Customer Portfolio Key`,`Customer Portfolio Customers State` from  `Customer Portfolio Fact` where `Customer Portfolio Customer Key`=? and `Customer Portfolio Product ID`=? and `Customer Portfolio Customers State`='Removed'  ";
        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            array(
                $customer->id,
                $product_id,
            )
        );
        if ($row2 = $stmt2->fetch()) {
            $sql = "update  `Customer Portfolio Fact` set `Customer Portfolio Customers State`='Active'  where  `Customer Portfolio Key`=? ";
            $db->prepare($sql)->execute(
                array(
                    $row2['Customer Portfolio Key']
                )
            );

            stand_alone_process_aiku_fetch(
                $db,
                'Portfolio',
                $row2['Customer Portfolio Key']
            );

        }
        else {


            $sql  =
                "INSERT INTO `Customer Portfolio Fact` (`Customer Portfolio Store Key`,`Customer Portfolio Customer Key`,`Customer Portfolio Product ID`,`Customer Portfolio Creation Date`) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE `Customer Portfolio Customers State`='Active'";
            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $customer->get('Store Key'),
                    $customer->id,
                    $product_id,
                    $date

                )
            );
            $customer_portfolio_key = $db->lastInsertId();
            shopify_create_portfolio_item($store,$customer->id, $customer_portfolio_key);

            stand_alone_process_aiku_fetch(
                $db,
                'Portfolio',
                $customer_portfolio_key
            );


            if ($customer_portfolio_key) {
                $sql  = "INSERT INTO `Customer Portfolio Timeline` (`Customer Portfolio Timeline Customer Portfolio Key`,`Customer Portfolio Timeline Action`,`Customer Portfolio Timeline Date`) VALUES (?,?,?)";
                $stmt = $db->prepare($sql);
                $stmt->execute(
                    array(
                        $customer_portfolio_key,
                        'Add',
                        $date

                    )
                );
            }


        }

        new_housekeeping_fork(
            'au_housekeeping', array(
            'type'         => 'update_portfolio_aiku',
            'customer_key' => $customer->id,
            'product_id'   => $product_id,
        ), $account->get('Account Code')
        );


    }


    new_housekeeping_fork(
        'au_housekeeping', array(
        'type'         => 'customer_portfolio_changed',
        'customer_key' => $customer->id,
    ), $account->get('Account Code')
    );

    $response = array(
        'state'                           => 200,
        'products'                        => array_values($products),
        'result'                          => 'add',
        'update_metadata'                 => [
            'class_html' => []
        ],
        'number_added_items_to_portfolio' => $number_added_items_to_portfolio
    );
    echo json_encode($response);
    exit;


}

