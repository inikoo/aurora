<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 9 July 2018 at 21:10:06 GMT+8  Kuala Lumpur Malaysia
 Copyright (c) 2014, Inikoo

 Version 2.0
*/



if (!isset($_REQUEST['delivery_note_key'])) {
$response = array(
'state' => 'Error',
'msg'   => 'delivery_note_key needed'
);
echo json_encode($response);
exit;
}

if (!isset($_REQUEST['itf_key'])) {
$response = array(
'state' => 'Error',
'msg'   => 'itf_key needed'
);
echo json_encode($response);
exit;
}


/*

if (!isset($_REQUEST['quantity'])) {
$response = array(
'state' => 'Error',
'msg'   => 'quantity needed'
);
echo json_encode($response);
exit;
}

*/



if (!is_numeric($_REQUEST['delivery_note_key']) or $_REQUEST['delivery_note_key'] <= 0) {
    $response = array(
        'state' => 'Error',
        'msg'   => 'invalid delivery_note_key: '.$_REQUEST['delivery_note_key']
    );
    echo json_encode($response);
    exit;
}



if (!is_numeric($_REQUEST['itf_key']) or $_REQUEST['itf_key'] <= 0) {
    $response = array(
        'state' => 'Error',
        'msg'   => 'invalid itf_key: '.$_REQUEST['itf_key']
    );
    echo json_encode($response);
    exit;
}

/*

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

*/