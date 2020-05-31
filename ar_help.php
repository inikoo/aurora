<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 March 2016 at 15:38:13 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

require_once 'keyring/dns.php';
include_once 'keyring/au_deploy_conf.php';

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);


$state = json_decode($_REQUEST['state'], true);



$section = $state['section'];

if (empty($state['tab'])) {
    $state['tab'] = '';
}

if (empty($state['subtab'])) {
    $state['subtab'] = '';
}

if (empty($state['section'])) {
    $state['section'] = '';
}

if (empty($state['module'])) {
    $state['module'] = '';
}



$help_cache_key = 'au_help|'.hash('crc32', $state['module'].'|'.$state['section']).'|'.hash('crc32', $state['tab'].$state['subtab']);

if ($redis->exists($help_cache_key)  ) {

    $response = $redis->get($help_cache_key);



    echo $response;

} else {

    include_once 'common.php';

    include_once 'utils/help.functions.php';

    $response =
        array(
            'help' =>
                array(
                    'title'   => get_help_title($state, $user),
                    'content' => get_help_content($state, $smarty, $account, $user)
                ),
            'whiteboard'=>get_whiteboard($state['module'], $state['section'], $state['tab'], $state['subtab'], $modules, $db)
        );




    $redis->set($help_cache_key, json_encode($response));


    echo json_encode($response);

}



