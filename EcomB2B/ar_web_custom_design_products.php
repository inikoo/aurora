<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 June 2021 02:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

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

$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'get_custom_design_products_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_custom_design_products_html($data, $customer, $db);


        break;




}



function get_custom_design_products_html($data, $customer, $db) {


    $smarty = new Smarty();
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

    $webpage = $website->get_webpage('custom_design_products.sys');

    $content = $webpage->get('Content Data');


    $block_found = false;
    $block_key   = false;
    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'custom_design_products') {
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
            'msg'   => 'no custom products in webpage'
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


    $customer_custom_products = array();

    $sql = sprintf(
        "SELECT `Product ID`  FROM `Product Dimension` P   WHERE  `Product Customer Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Code`",
        $customer->id
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $product = get_object('Product', $row['Product ID']);

            $product->load_webpage();
            $customer_custom_products[] = array(
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
    }


    //  print_r($customer_custom_products);

    $smarty->assign('products', $customer_custom_products);

    $response = array(
        'state' => 200,
        'html'  => $smarty->fetch('theme_1/blk.custom_design_products.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl'),
    );


    echo json_encode($response);

}


