<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 9 July 2018 at 21:10:06 GMT+8  Kuala Lumpur Malaysia
 Copyright (c) 2014, Inikoo

 Version 2.0
*/



if (!isset($_REQUEST['quantity'])) {
$response = array(
'state' => 'Error',
'msg'   => 'quantity needed'
);
echo json_encode($response);
exit;
}





$qty = intval($_REQUEST['quantity']);


if ($qty < 0) {
    $response = array(
        'state' => 'Error',
        'msg'   => 'invalid quantity: '.$_REQUEST['quantity'].'=>',
        $qty
    );
    echo json_encode($response);
    exit;
}

