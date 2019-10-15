<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 10 November 2018 at 04:03:30 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2017, Inikoo

 Version 2.0
*/

use Rollbar\Rollbar;

if (defined('ROLLBACK_ACCESS_TOKEN')) {
    Rollbar::init(
        array(
            'access_token' => ROLLBACK_ACCESS_TOKEN,
            'environment'  => 'AU'
        )
    );
}




if (defined('SENTRY_DNS_AU')) {
    $sentry_config=array(
        'dsn' => SENTRY_DNS_AU,
    );
    if($release=get_current_git_commit()){
        $sentry_config['release']=$release;
    }
    Sentry\init($sentry_config);
}


/**
 * Get the hash of the current git HEAD
 * @param string $branch The git branch to check
 *
 * @return bool|false|string Either the hash or a boolean false
 */
function get_current_git_commit( $branch='master' ) {
    if ( $hash = file_get_contents( sprintf( '.git/refs/heads/%s', $branch ) ) ) {
        return $hash;
    } else {
        return false;
    }
}

