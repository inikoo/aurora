<?php
require 'vendor/autoload.php';

use Ratchet\Session\SessionProvider;
use Symfony\Component\HttpFoundation\Session\Session;

use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcachedSessionHandler;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;
$memcache_ip = '127.0.0.1';
$memcached   = new Memcached();
$memcached->addServer($memcache_ip, 11211);
$storage = new NativeSessionStorage(array(), new MemcachedSessionHandler($memcached));
//$session = new Session($storage);
//$session->start();
$loop   = React\EventLoop\Factory::create();
$pusher = new App\Publishers\Pusher;
$context = new React\ZMQ\Context($loop);
$pull    = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind('tcp://127.0.0.1:5555');
$pull->on(
    'message', array(
                 $pusher,
                 'onBlogEntry2'
             )
);
$webSock   = new React\Socket\Server('0.0.0.0:8081', $loop);
$webServer = new Ratchet\Server\IoServer(
    new Ratchet\Http\HttpServer(
        new Ratchet\Session\SessionProvider(
            new Ratchet\WebSocket\WsServer(new Ratchet\Wamp\WampServer($pusher)),
            new Handler\MemcachedSessionHandler($memcached)
        )
    )
    , $webSock
);
$loop->run();

