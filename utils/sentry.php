<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 10 November 2018 at 04:03:30 GMT+8, Kuala Lumpur, Malaysias

 Copyright (c) 2017, Inikoo

 Version 2.0
*/


if ( !preg_match('/bali|sasi|sakoi|geko/', gethostname()) ) {

    //$sentry_client = new Raven_Client('https://d16cc2751a024c0da7ad661e75f27814@sentry.io/1433833');
    $sentry_client = new Raven_Client('https://7c38fecb8a274f7e93cb0488ad22ca10@sentry.io/1319834');



    //
    $sentry_client->install();

}



