<?php
require __DIR__.'/keyring/ws.dns.php';
require __DIR__.'/vendor/autoload.php';

use App\Publishers\Pusher;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer;
use Ratchet\WebSocket\WsServer;
use React\ZMQ\Context;


$loop    = React\EventLoop\Factory::create();
$pusher  = new Pusher;
$context = new Context($loop);
$pull    = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind('tcp://'.LOCAL_IP.':5555');
$pull->on(
    'message', array(
                 $pusher,
                 'broadcast'
             )
);
$webSock = new React\Socket\Server('0.0.0.0:8081', $loop);


$webServer = new IoServer(
    new HttpServer(
        new WsServer(
            new WampServer($pusher)
        )
    ), $webSock
);
$loop->run();


