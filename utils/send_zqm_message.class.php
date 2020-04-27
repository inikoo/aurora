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
function send_zqm_message($message) {
    $sockets = get_zqm_message_sockets();
    foreach ($sockets as $socket) {
        $socket->send($message);
    }


}

function get_zqm_message_sockets() {
    include_once 'keyring/au_deploy_conf.php';

    $sockets = [];
    foreach (RATCHET_SERVERS as $server) {
        $context = new ZMQContext();
        $socket  = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect($server);
        $sockets[] = $socket;
    }

    return $sockets;

}


