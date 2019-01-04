<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 June 2018 at 13:46:31 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

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

    case 'get_out_of_stock_reminders_html':
        $data = prepare_values(
            $_REQUEST, array(
                         'device_prefix' => array(
                             'type'     => 'string',
                             'optional' => true
                         )
                     )
        );

        get_out_of_stock_reminders_html($data, $customer, $db);


        break;


    case 'add_out_of_stock_reminder':
        $data = prepare_values(
            $_REQUEST, array(
                         'pid' => array('type' => 'key')

                     )
        );

        $website=get_object('Website',$_SESSION['website_key']);

        add_out_of_stock_reminder($data, $customer, $website, $editor, $db);


        break;

    case 'remove_out_of_stock_reminder':
        $data = prepare_values(
            $_REQUEST, array(
                         'out_of_stock_reminder_key' => array('type' => 'numeric'),

                     )
        );

        remove_out_of_stock_reminder($data, $customer,$db);


        break;


}


function add_out_of_stock_reminder($data, $customer, $website, $editor, $db) {


    $customer->editor = $editor;


    $sql = sprintf(
        'INSERT INTO  `Back in Stock Reminder Fact` (`Back in Stock Reminder Customer Key`,`Back in Stock Reminder Product ID`,`Back in Stock Reminder Store Key`,`Back in Stock Reminder Website Key`,`Back in Stock Reminder Creation Date`) VALUES
		(%d,%d,%d,%d,%s) ON DUPLICATE KEY UPDATE `Back in Stock Reminder Key`=LAST_INSERT_ID(`Back in Stock Reminder Key`) 
		', $customer->id, $data['pid'], $website->get('Website Store Key'), $website->id,

        prepare_mysql(gmdate('Y-m-d H:i:s'))

    );

    //print $sql;



    $db->exec($sql);

    $out_of_stock_reminder_key = $db->lastInsertId();


    $response = array(
        'state'                     => 200,
        'out_of_stock_reminder_key' => $out_of_stock_reminder_key
    );
    echo json_encode($response);


}


function remove_out_of_stock_reminder($data,$customer, $db) {


  //  $customer->editor = $editor;


        $sql = sprintf('DELETE FROM `Back in Stock Reminder Fact` WHERE `Back in Stock Reminder Key`=%d and `Back in Stock Reminder Customer Key`  ', $data['out_of_stock_reminder_key'],$customer->id);


        $db->exec($sql);



    $response = array(
        'state'                     => 200,
    );
    echo json_encode($response);


}

function get_out_of_stock_reminders_html($data, $customer, $db) {



    $smarty               = new Smarty();
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

?>
