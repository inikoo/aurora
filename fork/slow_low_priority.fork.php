<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created:  23-09-2019 22:07:31 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 2.0
*/

include_once 'utils/object_functions.php';
include_once 'utils/new_fork.php';

function fork_take_webpage_screenshot($job) {

    global $account,$db,$session;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor) = $_data;


    $webpage = get_object('Webpage', $data['webpage_key']);
    $webpage->fork=true;

    try {
        $webpage->update_screenshots('Desktop');
    } catch (Exception $e) {

        echo $e->getMessage();
        print "error $url\n";
    }





}