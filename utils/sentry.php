<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 10 November 2018 at 04:03:30 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/


if (defined('SENTRY_DNS_AU')) {
    $sentry_config = array(
        'dsn' => SENTRY_DNS_AU,
    );
    if ($release = file_get_contents('release.txt')) {
        $sentry_config['release'] = $release;
    }
    Sentry\init($sentry_config);
}

