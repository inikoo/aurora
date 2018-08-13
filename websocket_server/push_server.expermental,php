<?php
// Didnt work
// needs wamp version 2 and autobahn dont support it
require 'vendor/autoload.php';

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;
use React\ZMQ\Context;


class Pusher extends Thruway\Peer\Client {
    public function onSessionStart($session, $transport) {

        $context = new React\ZMQ\Context($this->getLoop());
        $pull    = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555');
        $pull->on(
            'message', [
                         $this,
                         'receiver'
                     ]
        );

    }

    public function receiver($entry) {

        $entryData = json_decode($entry, true);


        if (!isset($entryData['channel'])) {
            return;
        }


        $this->getSession()->publish($entryData['channel'], [$entryData]);

    }




}


$router = new Router();
$realm  = "aurora";

$router->addInternalClient(new Pusher($realm, $router->getLoop()));
$router->addTransportProvider(new RatchetTransportProvider("0.0.0.0", 8081));
$router->start();



?>



