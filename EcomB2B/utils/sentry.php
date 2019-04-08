<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 10 November 2018 at 03:57:53 GMT+8, Kuala Lumpur, Malaysias

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

if ( !preg_match('/bali|sasi|sakoi/', gethostname()) ) {

    $sentry_client = new Raven_Client('https://518cd71faa40409b839c5e2c58a6b581@sentry.io/1433839');
    $sentry_client->install();
}
?>