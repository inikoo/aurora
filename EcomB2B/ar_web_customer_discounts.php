<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2021 19:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/

include_once 'ar_web_common_logged_in.php';
include_once 'utils/image_functions.php';

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

    case 'get_customer_discounts_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_customer_discounts_html($data, $customer, $db);


        break;


}


function get_customer_discounts_html($data, $customer, $db) {


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

    $webpage = $website->get_webpage('customer_discounts.sys');

    $content = $webpage->get('Content Data');


    $block_found = false;
    $block_key   = false;
    foreach ($content['blocks'] as $_block_key => $_block) {
        if ($_block['type'] == 'customer_discounts') {
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


    $placeholders = array(
        '[Customer Name]' => $customer->get('Name'),

    );


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


    $discounted_products = array();

    $sql = sprintf(
        "SELECT `Deal Component Term Allowances Label`,`Deal Component Terms`,`Deal Component Expiration Date`   FROM `Deal Component Dimension`   WHERE  `Deal Component Trigger Key`=%d  and `Deal Component Trigger`='Customer' and `Deal Component Terms Type`='Product Amount Ordered' ",
        $customer->id
    );


    // AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Code`

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $_metadata = json_decode($row['Deal Component Terms'], true);

            $product = get_object('Product', $_metadata['key']);
            if (in_array(
                $product->get('Product Web State'), [
                                                      'For Sale',
                                                      'Out of Stock'
                                                  ]
            )) {

                $expire = '';
                if ($row['Deal Component Expiration Date'] != '') {
                    $expire = sprintf(_('Valid until %s'), strftime("%a %e %B", strtotime($data['Deal Component Expiration Date'].' +0:00')));
                }

                $product->load_webpage();
                $discounted_products[] = array(
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
                    'allowance'            => $row['Deal Component Term Allowances Label'],
                    'expire'               => $expire,


                );
            }
        }
    }
    $discounted_families = array();


    $sql = sprintf(
        "SELECT `Deal Component Term Allowances Label`,`Deal Component Terms`,`Deal Component Expiration Date`  FROM `Deal Component Dimension`   WHERE  `Deal Component Trigger Key`=%d  and `Deal Component Trigger`='Customer' and `Deal Component Terms Type`='Category Amount Ordered' ",
        $customer->id
    );


    // AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Code`

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $_metadata = json_decode($row['Deal Component Terms'], true);

            $category = get_object('Category', $_metadata['key']);

            $category->load_webpage();

            $image_src = $category->get('Image');


            if (preg_match('/id=(\d+)/', $image_src, $matches)) {
                $image_key            = $matches[1];
                $image_mobile_website = 'wi.php?id='.$image_key.'&s=320x200';
                $image_website        = 'wi.php?id='.$image_key.'&s='.get_image_size($image_key, 432, 330, 'fit_highest');

            } else {
                $image_mobile_website = $image_src;
                $image_website        = $image_src;
            }
            $expire = '';
            if ($row['Deal Component Expiration Date'] != '') {
                $expire = sprintf(_('Valid until %s'), strftime("%a %e %B", strtotime($data['Deal Component Expiration Date'].' +0:00')));
            }

            $discounted_families[] = array(
                'type' => 'category',


                'link'        => $category->webpage->get('URL'),
                'header_text' => $category->get('Label'),
                'allowance'   => $row['Deal Component Term Allowances Label'],
                'expire'      => $expire,

                'image_src'            => $image_src,
                'image_mobile_website' => $image_mobile_website,
                'image_website'        => $image_website

            );
        }
    }


    $smarty->assign('products', $discounted_products);
    $smarty->assign('families', $discounted_families);


    $html = $smarty->fetch('theme_1/blk.customer_discounts.'.$theme.'.EcomB2B'.($data['device_prefix'] != '' ? '.'.$data['device_prefix'] : '').'.tpl');

    $html = strtr($html, $placeholders);


    $response = array(
        'state' => 200,
        'html'  => $html,
    );


    echo json_encode($response);

}


