<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Sat 26 Oct 2019 01:52:41 +0800 MYT MYT Kuala Lumpur, Malaysia
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

    case 'get_orders':


        get_orders($customer, $db);


        break;

}

/**
 * @param $customer \Customer
 * @param $db \PDO
 */
function get_orders($customer, $db) {



    $data=array();

    $sql = "SELECT `Order Key`,`Order Public ID`,`Customer Client Code`
            FROM 
                `Order Dimension` left join `Customer Client Dimension` on (`Order Customer Client Key`=`Customer Client Key`)
            WHERE   `Order Customer Key`=?
            ";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $customer->id
        )
    );
    while ($row = $stmt->fetch()) {
        $data[]=array(
            $row['Order Public ID'],
            $row['Customer Client Code'],
        );
    }


    echo json_encode(
        array('data'=>$data)

    );

}


