<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 April 2018 at 12:54:36 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
/** @var PDO $db */
/** @var Customer $customer */


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

        category_products($data, $db, $customer->id, $order ?? false);
        break;


    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
}


function category_products($data, $db, $customer_key, $order)
{
    $website  = get_object('Website', $_SESSION['website_key']);
    $store    = get_object('Store', $website->get('Website Store Key'));
    $currency = $store->get('Store Currency Code');


    $labels = $website->get('Localised Labels');


    $favourite              = array();
    $out_of_stock_reminders = array();
    $ordered_products       = array();
    $stock                  = array();
    $discounts              = array();
    $gold_reward            = array();


    $sql = sprintf(
        'SELECT `Customer Favourite Product Product ID`,`Customer Favourite Product Key` FROM `Customer Favourite Product Fact` WHERE `Customer Favourite Product Customer Key`=%d ',
        $customer_key
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $favourite[$row['Customer Favourite Product Product ID']] = $row['Customer Favourite Product Key'];
        }
    }


    $sql = sprintf(
        'SELECT `Back in Stock Reminder Product ID`,`Back in Stock Reminder Key` FROM `Back in Stock Reminder Fact` WHERE `Back in Stock Reminder Customer Key`=%d ',
        $customer_key
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $out_of_stock_reminders[$row['Back in Stock Reminder Product ID']] = $row['Back in Stock Reminder Key'];
        }
    }

    if ($order) {
        $sql = sprintf(
            'SELECT `Product ID`,`Order Quantity` FROM `Order Transaction Fact` WHERE `Order Key`=%d ',
            $order->id
        );
        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                $ordered_products[$row['Product ID']] = $row['Order Quantity'];
            }
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
            "SELECT `Product Availability State`,`Product Availability`,`Product ID`,`Product Web Configuration`,`Product Web State` FROM `Customer Favourite Product Fact`    left join `Product Dimension` P on (P.`Product ID`=`Customer Favourite Product Product ID`)  WHERE `Customer Favourite Product Customer Key`=? ";

        $stmt = $db->prepare($sql);
        $stmt->execute(
            array($customer_key)
        );
        while ($row = $stmt->fetch()) {
            //'For Sale','Out of Stock','Discontinued','Offline'

            if ($row['Product Web State'] == 'For Sale') {
                $stock[$row['Product ID']] = array(
                    $row['Product Availability State'],
                    get_stock_label($labels, $show_stock_value, $row['Product Availability State'], $row['Product Availability'])
                );
            } else {
                $stock_label               = (!empty($labels['_stock_OutofStock']) ? $labels['_stock_OutofStock'] : _('Out of stock'));
                $stock[$row['Product ID']] = array(
                    'OutofStock',
                    $stock_label
                );
            }
        }
    }

    $do_stock = false;
    if (($with_category_products and ($website->settings('Display Stock Levels in Category') == 'Hint_Bar' or $website->settings('Display Stock Levels in Category') == 'Dot')) or (!$with_category_products and $website->settings('Display Stock Levels in Product')
            == 'Yes')) {
        $do_stock = true;
    }

    $show_stock_value = $website->settings('Display Stock Quantity');
    if ($show_stock_value == '') {
        $show_stock_value = 'No';
    }


    // gold reward discounts



    $family_key = null;

    $sql  =
        "select `Webpage Scope`,`Webpage Scope Key` from `Page Store Dimension`   where `Page Key`=?  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($data['webpage_key'])
    );
    while ($row = $stmt->fetch()) {
        if($row['Webpage Scope']=='Category Products'){
            $family_key = $row['Webpage Scope Key'];
        }elseif($row['Webpage Scope']=='Product'){


            $product=get_object('Product',$row['Webpage Scope Key']);

            $family_key = $product->get('Product Family Category Key');

        }

    }


    $gold_reward_data = null;

    $sql  =
        "select `Deal Component Allowance Label`,`Deal Component Allowance` from `Deal Component Dimension`  where  `Deal Component Status`='Active' and `Deal Component Trigger`='Category'  and `Deal Component Terms Type`='Category Quantity Ordered' and `Deal Component Trigger Key`=?  ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($family_key)
    );
    while ($row = $stmt->fetch()) {

        $gold_reward_data = [
            'label'    => $row['Deal Component Allowance Label'],
            'discount' => $row['Deal Component Allowance']
        ];
    }

    $gold_reward_families = [];

    $families = [];

    $sql  =
        "select `Product Availability State`,`Product Availability`,`Product ID`,`Product Price`,`Product Family Category Key`,`Product Price`,`Product Units Per Case`,`Product Unit Label` 
        from `Website Webpage Scope Map` 
        left join `Product Dimension` P on (P.`Product ID`=`Website Webpage Scope Scope Key`) 
        where `Website Webpage Scope Scope`='Product' and `Website Webpage Scope Webpage Key`=? ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array($data['webpage_key'])
    );
    while ($row = $stmt->fetch()) {
        if ($do_stock) {
            $stock[$row['Product ID']] = array(
                $row['Product Availability State'],
                get_stock_label($labels, $show_stock_value, $row['Product Availability State'], $row['Product Availability'])
            );
        }

        $families[$row['Product Family Category Key']] = $row['Product Family Category Key'];


        if ($gold_reward_data) {
            $disc = 1 - $gold_reward_data['discount'];


            $price = money($disc * $row['Product Price'], $currency);


            if ($row['Product Units Per Case'] != 1) {
                $price_unit = '('.preg_replace('/PLN/', 'zł ', money($disc * $row['Product Price'] / $row['Product Units Per Case'], $currency)).'/'.$row['Product Unit Label'].')';
            } else {
                $price_unit = '';
            }

            $gold_reward[$row['Product ID']] = [
                'label'          => $gold_reward_data['label'],
                'percentage'     => '&darr;'.percentage($gold_reward_data['discount'], 1, 0),
                'price'          => $price,
                'price_per_unit' => $price_unit,
                'applied'        => false,
                'pop_up'         => [
                    'type'       => 'gold_reward',
                    'link_label' => !empty($labels['_more_info']) ? $labels['_more_info'] : 'More info',
                    'link'       => !empty($labels['_gold_reward_url']) ? $labels['_gold_reward_url'] : '',
                    'text'       => !empty($labels['_gold_reward_inactive']) ? $labels['_gold_reward_inactive'] : 'Become a Gold Reward Member today'
                ]
            ];
        }
    }


    if ($order) {
        $sql = sprintf(
            'SELECT P.`Product ID`,`Fraction Discount`,`Deal Info`,`Product Price`,`Product Unit Label`,`Deal Campaign Code`,`Product Units Per Case` ,OTDB.`Category Key` 
            FROM `Order Transaction Deal Bridge` OTDB
            left join `Product Dimension` P on (P.`Product ID`=OTDB.`Product ID`) 
            left join `Deal Campaign Dimension` C on (OTDB.`Deal Campaign Key`=C.`Deal Campaign Key`)
             WHERE `Order Key`=%d ',
            $order->id
        );


        if ($result = $db->query($sql)) {
            foreach ($result as $row) {
                if (isset($gold_reward[$row['Product ID']]) and $row['Fraction Discount'] and $row['Fraction Discount'] > 0) {
                    $apply_to_family = false;
                    if ($row['Deal Campaign Code'] == 'VL') {
                        $apply_to_family = $row['Category Key'];
                    }


                    $disc = 1 - $row['Fraction Discount'];


                    $price = money($disc * $row['Product Price'], $currency);


                    if ($row['Product Units Per Case'] != 1) {
                        $price_unit = '('.preg_replace('/PLN/', 'zł ', money($disc * $row['Product Price'] / $row['Product Units Per Case'], $currency)).'/'.$row['Product Unit Label'].')';
                    } else {
                        $price_unit = '';
                    }


                    $gold_reward[$row['Product ID']] = [
                        'info'           => $row['Deal Info'],
                        'percentage'     => '&darr;'.percentage($row['Fraction Discount'], 1, 0),
                        'price'          => $price,
                        'price_per_unit' => $price_unit,
                        'applied'        => true,
                        'pop_up'         => [
                            'type'       => 'gold_reward',
                            'link_label' => !empty($labels['_more_info']) ? $labels['_more_info'] : 'More info',
                            'link'       => !empty($labels['_gold_reward_url']) ? $labels['_gold_reward_url'] : '',
                            'text'       => !empty($labels['_gold_reward_active']) ? $labels['_gold_reward_active'] : 'Gold Reward Discount applied'
                        ]

                    ];

                    if ($apply_to_family) {
                        $gold_reward_families[$apply_to_family] = $apply_to_family;
                    }
                }
            }
        }

        $gr_member = false;


        $sql = sprintf(
            "SELECT count(*) AS num FROM `Order Dimension` WHERE `Order Customer Key`=%d AND `Order Key`!=%d AND `Order Dispatched Date`>=%s AND `Order State`='Dispatched' ",
            $customer_key,
            $order->id,
            prepare_mysql(date('Y-m-d', strtotime(gmdate('Y-m-d H:i:s')." -30 days")).' 00:00:00')
        );




        if ($result = $db->query($sql)) {
            if ($_row = $result->fetch()) {
                //print_r($_row);
                if ($_row['num'] > 0) {
                    $gr_member = true;
                }
            }
        }


        if(    !in_array( $store->id,[18,22])   and DNS_ACCOUNT_CODE=='AWEU'){
            $gr_member = true;
        }
//        if(    $store->id != 3 and DNS_ACCOUNT_CODE=='ES'){
//            $gr_member = true;
//        }
//        if($store->id==1 and DNS_ACCOUNT_CODE=='AW'){
//            $gr_member = true;
//        }


        if ($gr_member and count($families) > 0) {
            $sql = sprintf(
                "select `Deal Component Trigger Key` from `Deal Component Dimension` where `Deal Component Status`='Active'  and `Deal Component Trigger`='Category' and `Deal Component Trigger Key` in (%s) and `Deal Component Terms Type`='Order Interval'  ",
                join(',', $families)
            );


            $stmt = $db->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $apply_to_family                        = $row['Deal Component Trigger Key'];
                $gold_reward_families[$apply_to_family] = $apply_to_family;
            }
        }
        // print_r($gold_reward_families);exit;


    }


    echo json_encode(
        array(
            'state'                  => 200,
            'favourite'              => $favourite,
            'out_of_stock_reminders' => $out_of_stock_reminders,
            'ordered_products'       => $ordered_products,
            'stock'                  => $stock,
            'discounts'              => [],
            'gold_reward'            => $gold_reward,
            'gold_reward_families'   => array_values($gold_reward_families),
            'version'=>'1'


        )
    );
    exit;
}


function get_stock_label($labels, $show_stock_value, $stock_state, $availability)
{
    switch ($stock_state) {
        case 'OnDemand':
            $stock_label = (!empty($labels['_stock_OnDemand']) ? $labels['_stock_OnDemand'] : _('Product made on demand'));
            break;
        case 'Excess':
            $stock_label = (!empty($labels['_stock_Excess']) ? $labels['_stock_Excess'] : _('Plenty of stock'));
            if ($show_stock_value == 'Yes') {
                $stock_label .= ' ('.number($availability).')';
            }
            break;
        case 'Normal':
            $stock_label = (!empty($labels['_stock_Normal']) ? $labels['_stock_Normal'] : _('Plenty of stock'));
            if ($show_stock_value == 'Yes') {
                $stock_label .= ' ('.number($availability).')';
            }
            break;
        case 'Low':
            $stock_label = (!empty($labels['_stock_Low']) ? $labels['_stock_Low'] : _('Limited stock'));
            if ($show_stock_value == 'Yes') {
                $stock_label .= ' ('.number($availability).')';
            }
            break;
        case 'VeryLow':
            $stock_label = (!empty($labels['_stock_OutofStock']) ? $labels['_stock_OutofStock'] : _('Very low stock'));
            if ($show_stock_value == 'Yes' or $show_stock_value == 'Only_if_very_low') {
                $stock_label .= ' ('.number($availability).')';
            }
            break;

        case 'OutofStock':
        case 'Error':

            $stock_label = (!empty($labels['_stock_OutofStock']) ? $labels['_stock_OutofStock'] : _('Out of stock'));
            break;
        default:
            $stock_label = $stock_state;
    }


    return $stock_label;
}
