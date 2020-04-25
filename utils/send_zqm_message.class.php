<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   26 April 2020  00:43::28  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

/**
 * @param  $message
 *
 * @throws \ZMQSocketException
 */
function send_zqm_message($message){
    $socket=get_zqm_message_socket();
    $socket->send($message);

}

function get_zqm_message_socket(){
    include_once 'keyring/au_deploy_conf.php';
    $context = new ZMQContext();
    $socket= $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
    foreach (RATCHET_SERVERS as $server){
        $socket->connect($server);
    }
    return $socket;

}


