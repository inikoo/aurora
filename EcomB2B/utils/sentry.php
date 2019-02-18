<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 10 November 2018 at 03:57:53 GMT+8, Kuala Lumpur, Malaysias

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

if ( !preg_match('/bali/', gethostname()) ) {

    $sentry_client = new Raven_Client('https://bdeef00d9ed04614a5b3245c0ba178ec@sentry.io/1319896');
    $sentry_client->install();
}
?>