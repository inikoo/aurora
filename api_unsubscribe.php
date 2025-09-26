<?php



$s=$_REQUEST['s'];
$a=$_REQUEST['a'];


$response = array(
    'state'   => 'Unsubscribed',
    'msg'     => 'test',
);
echo json_encode($response);
exit;