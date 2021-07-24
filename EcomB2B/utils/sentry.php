<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 10 November 2018 at 03:57:53 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

if (defined('SENTRY_DNS_ECOM')) {
    $sentry_config = array(
        'dsn' => SENTRY_DNS_ECOM,
        'release'=>'__AURORA_RELEASE__'
    );
    Sentry\init($sentry_config);
}

