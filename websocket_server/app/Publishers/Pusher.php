<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 July 2018 at 14:52:56 GMT+8 Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo
 Version 3
*/

namespace App\Publishers;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {
    protected $subscribed_channels = array();

    public function onSubscribe(ConnectionInterface $conn, $channel) {
        print_r($conn);
        print "somebody subscribed to ".$channel."\n";
        $this->subscribed_channels[$channel->getId()] = $channel;
    }

    /**
     * @param $entry
     */
    public function broadcast($entry) {


        $data = json_decode($entry, true);
        if (!array_key_exists($data['channel'], $this->subscribed_channels)) {
            return;
        }
        $channel = $this->subscribed_channels[$data['channel']];
        $channel->broadcast($data);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        print "somebody un_subscribed to ".$topic;
    }

    public function onOpen(ConnectionInterface $conn) {
        // $cookies = $conn->httpRequest->getHeader('Cookie');
        $handshake = array(
            'msg' => 'hello',
        );
        $conn->send(json_encode($handshake));
    }

    public function onClose(ConnectionInterface $conn) {
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        // $conn->close();
        //print_r($topic);
        //print_r($event);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
    }
}

