<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 November 2017 at 22:04:13 GMT+8, Sanir, Bali, Indonesia
 Copyright (c) 20167 Inikoo

 Version 3

*/


require_once 'common.php';
require_once 'utils/ar_web_common.php';


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


if (!$customer->id) {
    $response = array(
        'state' => 400,
        'resp'  => 'not customer'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {

    case 'send_reminder':
        $data = prepare_values(
            $_REQUEST, array(
                         'pid'        => array('type' => 'key'),
                       
                     )
        );

        send_reminder($data, $customer, $website, $editor, $db);


        break;

}


function send_reminder($data, $customer, $website, $editor, $db) {


    $customer->editor = $editor;



        $product=get_object('Product',$data['pid']);
        $sql=sprintf('insert into  `Customer Favourite Product Fact` (`Customer Favourite Product Customer Key`,`Customer Favourite Product Product ID`,`Customer Favourite Product Store Key`,`Customer Favourite Product Creation Date`) values
		(%d,%d,%d,%s) on duplicate key update `Customer Favourite Product Store Key`=%d
		',
                     $data['customer_key'],
                     $product->id,
                     $product->data['Product Store Key'],

                     prepare_mysql(gmdate('Y-m-d H:i:s')),
                     $product->data['Product Store Key']

        );

       // print $sql;
        $db->exec($sql);

        $favourite_key=$db->lastInsertId();
        $pid=$product->id;

    

    $response= array('state'=>200,'favourite_key'=>$favourite_key,'pid'=>$pid);
    echo json_encode($response);



}


function remove_reminder($data, $customer, $website, $editor, $db) {


    $customer->editor = $editor;

    if ($data['favourite_key']) {

        $sql=sprintf('delete from `Customer Favourite Product Fact` where `Customer Favourite Product Key`=%d ',$data['favourite_key'] );


        $db->exec($sql);

        $favourite_key=0;
        $pid=$data['pid'];

    }else {

        $product=get_object('Product',$data['pid']);
        $sql=sprintf('insert into  `Customer Favourite Product Fact` (`Customer Favourite Product Customer Key`,`Customer Favourite Product Product ID`,`Customer Favourite Product Store Key`,`Customer Favourite Product Creation Date`) values
		(%d,%d,%d,%s) on duplicate key update `Customer Favourite Product Store Key`=%d
		',
                     $data['customer_key'],
                     $product->id,
                     $product->data['Product Store Key'],

                     prepare_mysql(gmdate('Y-m-d H:i:s')),
                     $product->data['Product Store Key']

        );

       // print $sql;
        $db->exec($sql);

        $favourite_key=$db->lastInsertId();
        $pid=$product->id;

    }

    $response= array('state'=>200,'favourite_key'=>$favourite_key,'pid'=>$pid);
    echo json_encode($response);



}


?>
