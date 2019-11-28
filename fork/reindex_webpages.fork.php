<?php
/*
 Author: Raul Perusquia <rulovico@gmail.com>
 Created:  28 November 2019  09:26::32  +0100, Malaga, Spain
 Copyright (c) 2019, Inikoo

 Version 2.0
*/


function fork_reindex_webpages($job) {

    global $account,$db,$session;// remove the global $db and $account is removed

    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    list($account, $db, $data, $editor, $session) = $_data;



    switch ($data['type']) {


        case 'reindex_webpages_items':

            foreach($data['webpages_keys'] as $webpage_key){
                $webpage=get_object('Webpage',$webpage_key);
                $webpage->editor=$editor;
                $webpage->fork=true;
                $webpage->reindex_items();
            }


            break;



        default:
            break;

    }


    return false;
}


