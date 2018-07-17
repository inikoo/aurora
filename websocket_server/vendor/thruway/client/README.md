[![Build Status](https://travis-ci.org/voryx/Thruway.svg?branch=master)](https://travis-ci.org/voryx/Thruway)

Thruway
===========

Thruway Client is an open source client for [Thruway](https://github.com/voryx/Thruway) and the [WAMP (Web Application Messaging Protocol)](http://wamp-proto.org/), for PHP.

Thruway uses ([reactphp](http://reactphp.org/)); an event-driven, non-blocking I/O model, perfect for modern real-time applications.

### <a name="features"></a>Supported WAMP Features

**Basic Spec** [read more](https://github.com/tavendo/WAMP/blob/master/spec/basic.md)
* Publish and Subscribe
* Remote Procedure Calls
* Websocket Transport
* Internal Transport\*
* JSON serialization

**Advanced Spec** [read more](https://github.com/tavendo/WAMP/blob/master/spec/advanced.md)
* RawSocket Transport
* Authentication
  * WAMP Challenge-Response Authentication
  * Custom Authentication Methods
* Publish & Subscribe
  * Subscriber Black and Whitelisting
  * Publisher Exclusion
  * Publisher Identification
* Remote Procedure Calls
  * Caller Identification
  * Progressive Call Results
  * Caller Exclusion
  * Canceling Calls

\* _Thruway specific features_



Requirements
------------

Thruway Client is only supported on PHP 5.6 and up.

### Quick Start with Composer

The below instructions actually install the Thruway Router and Client for test purposes.
The client can also be installed without the router in your own project.

Create a directory for the test project

      $ mkdir thruway

Switch to the new directory

      $ cd thruway

Download Composer [more info](https://getcomposer.org/doc/00-intro.md#downloading-the-composer-executable)

      $ curl -sS https://getcomposer.org/installer | php
      
Download Thruway and dependencies

      $ php composer.phar require voryx/thruway

Start the WAMP router

      $ php vendor/voryx/thruway/Examples/SimpleWsRouter.php
    
Thruway is now running on 127.0.0.1 port 9090 

### PHP Client Example

```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Thruway\ClientSession;
use Thruway\Peer\Client;
use Thruway\Transport\PawlTransportProvider;

$client = new Client("realm1");
$client->addTransportProvider(new PawlTransportProvider("ws://127.0.0.1:9090/"));

$client->on('open', function (ClientSession $session) {

    // 1) subscribe to a topic
    $onevent = function ($args) {
        echo "Event {$args[0]}\n";
    };
    $session->subscribe('com.myapp.hello', $onevent);

    // 2) publish an event
    $session->publish('com.myapp.hello', ['Hello, world from PHP!!!'], [], ["acknowledge" => true])->then(
        function () {
            echo "Publish Acknowledged!\n";
        },
        function ($error) {
            // publish failed
            echo "Publish Error {$error}\n";
        }
    );

    // 3) register a procedure for remoting
    $add2 = function ($args) {
        return $args[0] + $args[1];
    };
    $session->register('com.myapp.add2', $add2);

    // 4) call a remote procedure
    $session->call('com.myapp.add2', [2, 3])->then(
        function ($res) {
            echo "Result: {$res}\n";
        },
        function ($error) {
            echo "Call Error: {$error}\n";
        }
    );
});


$client->start();
```

### Javascript Clients

You can also use [AutobahnJS](https://github.com/tavendo/AutobahnJS) or any other WAMPv2 compatible client.

Here are some [examples] (https://github.com/tavendo/AutobahnJS#show-me-some-code)

Here's a [plunker](http://plnkr.co/edit/8vcBDUzIhp48JtuTGIaj?p=info) that will allow you to run some tests against a local router

For AngularJS on the frontend, use the [Angular WAMP](https://github.com/voryx/angular-wamp) wrapper.

