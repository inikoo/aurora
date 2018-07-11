<?php


$context = new ZMQContext();
$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');



$socket->connect("tcp://localhost:5555");

$data=array(
    'channel'=>'real_time'

);

$socket->send(json_encode($data));


?>