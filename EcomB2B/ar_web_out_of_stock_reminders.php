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


    case 'add_out_of_stock_reminder':
        $data = prepare_values(
            $_REQUEST, array(
                         'pid' => array('type' => 'key')

                     )
        );

        $website = get_object('Website', $_SESSION['website_key']);

        add_out_of_stock_reminder($data, $customer, $website, $editor, $db);


        break;

    case 'remove_out_of_stock_reminder':
        $data = prepare_values(
            $_REQUEST, array(
                         'out_of_stock_reminder_key' => array('type' => 'numeric'),

                     )
        );

        remove_out_of_stock_reminder($data, $customer, $db);


        break;


}

/**
 * @param $data
 * @param $customer \Public_Customer
 * @param $website \Public_Website
 * @param $editor
 * @param $db \PDO
 */
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

/**
 * @param $data
 * @param $customer \Public_Customer
 * @param $db \PDO
 */
function remove_out_of_stock_reminder($data, $customer, $db) {


    $sql = sprintf('DELETE FROM `Back in Stock Reminder Fact` WHERE `Back in Stock Reminder Key`=%d and `Back in Stock Reminder Customer Key` =%d ', $data['out_of_stock_reminder_key'], $customer->id);


    $db->exec($sql);


    $response = array(
        'state' => 200,
    );
    echo json_encode($response);


}
