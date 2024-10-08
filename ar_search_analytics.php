<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  09 January 2020  18:19::39  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

use Elasticsearch\ClientBuilder;

require_once 'utils/ar_common.php';

$client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
        ->setApiKey(ES_KEY1,ES_KEY2)
        ->setSSLVerification(ES_SSL)
        ->build();


$query_data = prepare_values(
    $_REQUEST, array(
                 'search_index' => array('type' => 'string'),
                 'mtime'        => array('type' => 'string'),
                 'action'       => array('type' => 'string'),
                 'click_url'    => array('type' => 'string'),
                 'click_pos' => array('type' => 'string'),

             )
);


$now   = DateTime::createFromFormat('U.u', sprintf('%.f', microtime(true)));
$mtime = $now->format("U.u");

$time_diff = $mtime - $query_data['mtime'];


$params = [
    'index' => strtolower('au_q_search_analytics_'.DNS_ACCOUNT_CODE),
    'id'    => $query_data['search_index'],
    'retry_on_conflict'=>4,
    'body'  => [
        'doc' => [
            'action'     => $query_data['action'],
            'delta_time' => $time_diff
        ],

    ]
];


if ($query_data['click_url'] != '') {
    $params['body']['doc']['click_url'] = $query_data['click_url'];
    $params['body']['doc']['click_pos'] = $query_data['click_pos'];

}

$response = $client->update($params);




