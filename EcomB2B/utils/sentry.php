<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 10 November 2018 at 03:57:53 GMT+8, Kuala Lumpur, Malaysias

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

if ( !preg_match('/bali/', gethostname()) ) {

    $sentry_client = new Raven_Client('https://ca602819cbd14ce99a6d3ab94e1c5f04@sentry.io/1329969');
    $sentry_client->install();
}
?>