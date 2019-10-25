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

$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'get_portfolio_items':


        get_portfolio_items($customer, $db);


        break;


    case 'update_portfolio':
        $data = prepare_values(
            $_REQUEST, array(
                         'pid'           => array('type' => 'key'),
                         'favourite_key' => array('type' => 'numeric'),

                     )
        );

        update_portfolio($data, $customer, $editor, $db);


        break;

}

function update_portfolio($data, $customer, $editor, $db) {


    $customer->editor = $editor;

    if ($data['favourite_key']) {

        $sql = sprintf('DELETE FROM `Customer Favourite Product Fact` WHERE `Customer Favourite Product Key`=%d ', $data['favourite_key']);


        $db->exec($sql);

        $favourite_key = 0;
        $pid           = $data['pid'];

    } else {

        $product = get_object('Product', $data['pid']);
        $sql     = sprintf(
            'INSERT INTO  `Customer Favourite Product Fact` (`Customer Favourite Product Customer Key`,`Customer Favourite Product Product ID`,`Customer Favourite Product Store Key`,`Customer Favourite Product Creation Date`) VALUES
		(%d,%d,%d,%s) ON DUPLICATE KEY UPDATE `Customer Favourite Product Store Key`=%d
		', $customer->id, $product->id, $product->data['Product Store Key'],

            prepare_mysql(gmdate('Y-m-d H:i:s')), $product->data['Product Store Key']

        );

        // print $sql;
        $db->exec($sql);

        $favourite_key = $db->lastInsertId();
        $pid           = $product->id;

    }

    $response = array(
        'state'         => 200,
        'favourite_key' => $favourite_key,
        'pid'           => $pid
    );
    echo json_encode($response);


}

function get_portfolio_items($customer, $db) {

    $portfolio_items=array();


    $data=array();

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
        $data[]=array(
            $row['Product Code'],
            ($row['Product Units Per Case']>1?$row['Product Units Per Case'].'x' :'').$row['Product Name']
        );
    }


    echo json_encode(
        array('data'=>$data)

    );

}


