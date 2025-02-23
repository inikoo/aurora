<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 April 2018 at 15:22:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
require_once 'utils/aiku_stand_alone_process_aiku_fetch.php';

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'get_favourites_html':
        $data = prepare_values(
            $_REQUEST,
            array(
                'device_prefix' => array(
                    'type'     => 'string',
                    'optional' => true
                )
            )
        );

        get_favourites_html($data, $customer, $db);


        break;


    case 'update_favourite':
        $data = prepare_values(
            $_REQUEST,
            array(
                'pid'           => array('type' => 'key'),
                'favourite_key' => array('type' => 'numeric'),

            )
        );

        update_favourite($data, $customer, $editor, $db);


        break;
}

/**
 * @param $data
 * @param $customer \Public_Customer
 * @param $editor
 * @param $db \PDO
 */
function update_favourite($data, $customer, $editor, $db)
{
    $customer->editor = $editor;

    if ($data['favourite_key']) {
        $sql = sprintf('DELETE FROM `Customer Favourite Product Fact` WHERE `Customer Favourite Product Key`=%d ', $data['favourite_key']);


        $db->exec($sql);

        $favourite_key = 0;
        $pid           = $data['pid'];
        stand_alone_process_aiku_fetch(
            'DeleteFavourite',
            $data['favourite_key'],
            null,
            null,
            [
                'unfavourited_at'=>gmdate('Y-m-d H:i:s')
            ]
        );
    } else {
        $product = get_object('Product', $data['pid']);
        $sql     = sprintf(
            'INSERT INTO  `Customer Favourite Product Fact` (`Customer Favourite Product Customer Key`,`Customer Favourite Product Product ID`,`Customer Favourite Product Store Key`,`Customer Favourite Product Creation Date`) VALUES
		(%d,%d,%d,%s) ON DUPLICATE KEY UPDATE `Customer Favourite Product Store Key`=%d
		',
            $customer->id,
            $product->id,
            $product->data['Product Store Key'],

            prepare_mysql(gmdate('Y-m-d H:i:s')),
            $product->data['Product Store Key']

        );
        $db->exec($sql);
        $favourite_key = $db->lastInsertId();
        stand_alone_process_aiku_fetch('Favourite', $favourite_key);


        if (!$favourite_key) {
            throw new Exception('Error inserting Customer Favourite Product Fact');
        }
        $pid = $product->id;
    }

    $customer->fork_index_elastic_search('create_elastic_index_object', ['favourites']);

    $response = array(
        'state'         => 200,
        'favourite_key' => $favourite_key,
        'pid'           => $pid
    );
    echo json_encode($response);
}

function get_favourites_html($data, $customer, $db)
{
    $smarty               = new Smarty();
    $smarty->caching_type = 'redis';
    $smarty->setTemplateDir('templates');
    $smarty->setCompileDir('server_files/smarty/templates_c');
    $smarty->setCacheDir('server_files/smarty/cache');
    $smarty->setConfigDir('server_files/smarty/configs');
    $smarty->addPluginsDir('./smarty_plugins');

    $order = get_object('Order', $customer->get_order_in_process_key());

    $website = get_object('Website', $_SESSION['website_key']);
    $theme   = $website->get('Website Theme');

    $store = get_object('Store', $website->get('Website Store Key'));

    $webpage = $website->get_webpage('favourites.sys');

    $content = $webpage->get('Content Data');


    $block_found = false;
    $block_key   = false;
    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'favourites') {
            $block       = $_block;
            $block_key   = $_block_key;
            $block_found = true;
            break;
        }
    }

    if (!$block_found) {
        $response = array(
            'state' => 200,
            'html'  => '',
            'msg'   => 'no favourites in webpage'
        );
        echo json_encode($response);
        exit;
    }
    $smarty->assign('order', $order);
    $smarty->assign('customer', $customer);
    $smarty->assign('website', $website);
    $smarty->assign('store', $store);

    $smarty->assign('key', $block_key);
    $smarty->assign('data', $block);
    $smarty->assign('labels', $website->get('Localised Labels'));
    $smarty->assign('settings', $website->settings);

    $smarty->assign('logged_in', true);
    $smarty->assign('order_key', $order->id);


    $favourite_products = array();

    $sql = sprintf(
        "SELECT `Customer Favourite Product Product ID`  FROM `Customer Favourite Product Fact` B  LEFT JOIN `Product Dimension` P ON (`Customer Favourite Product Product ID`=P.`Product ID`)  WHERE  `Customer Favourite Product Customer Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Code`",
        $customer->id
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = get_object('Product', $row['Customer Favourite Product Product ID']);

            $product->load_webpage();
            $favourite_products[] = array(
                'type'                 => 'product',
                'product_id'           => $product->id,
                'web_state'            => $product->get('Web State'),
                'price'                => $product->get('Price'),
                'rrp'                  => $product->get('RRP'),
                'header_text'          => '',
                'code'                 => $product->get('Code'),
                'name'                 => $product->get('Name'),
                'link'                 => $product->webpage->get('URL'),
                'webpage_code'         => $product->webpage->get('Webpage Code'),
                'webpage_key'          => $product->webpage->id,
                'image_src'            => $product->get('Image'),
                'image_mobile_website' => $product->get('Image Mobile In Family Webpage'),
                'image_website'        => '',
                'out_of_stock_class'   => $product->get('Out of Stock Class'),
                'out_of_stock_label'   => $product->get('Out of Stock Label'),
                'sort_code'            => $product->get('Code File As'),
                'sort_name'            => $product->get('Product Name'),


            );
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    //  print_r($favourite_products);

    $smarty->assign('products', $favourite_products);

    $response = array(
        'state' => 200,
        'html'  => $smarty->fetch('theme_1/blk.favourites.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
    );


    echo json_encode($response);
}


