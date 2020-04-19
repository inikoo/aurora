<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   12 January 2020  13:40::07  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require '../keyring/dns.php';

use Elasticsearch\ClientBuilder;

require '../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();



$params['body'] = array(
    'actions' => array(
        array(
            'add' => array(
                'index'   => 'au_web_search_analytics',
                'alias'   => 'au_web_search_analytics_'.strtolower(DNS_ACCOUNT_CODE),
                "routing" => DNS_ACCOUNT_CODE,
                "filter"  => [
                    "term" => [
                        "tenant" => DNS_ACCOUNT_CODE
                    ]
                ]
            )
        )
    )
);
$client->indices()->updateAliases($params);

$params['body'] = array(
    'actions' => array(
        array(
            'add' => array(
                'index'   => 'au_web_search',
                'alias'   => 'au_web_search_'.strtolower(DNS_ACCOUNT_CODE),
                "routing" => DNS_ACCOUNT_CODE,
                "filter"  => [
                    "term" => [
                        "tenant" => DNS_ACCOUNT_CODE
                    ]
                ]
            )
        )
    )
);
$client->indices()->updateAliases($params);



$params['body'] = array(
    'actions' => array(
        array(
            'add' => array(
                'index'   => 'au_q_search_analytics',
                'alias'   => 'au_q_search_analytics_'.strtolower(DNS_ACCOUNT_CODE),
                "routing" => DNS_ACCOUNT_CODE,
                "filter"  => [
                    "term" => [
                        "tenant" => DNS_ACCOUNT_CODE
                    ]
                ]
            )
        )
    )
);
$client->indices()->updateAliases($params);

$params['body'] = array(
    'actions' => array(
        array(
            'add' => array(
                'index'   => 'au_search',
                'alias'   => 'au_search_'.strtolower(DNS_ACCOUNT_CODE),
                "routing" => DNS_ACCOUNT_CODE,
                "filter"  => [
                    "term" => [
                        "tenant" => DNS_ACCOUNT_CODE
                    ]
                ]
            )
        )
    )
);
$client->indices()->updateAliases($params);





$params['body'] = array(
    'actions' => array(
        array(
            'add' => array(
                'index'   => 'au_customers',
                'alias'   => 'au_customers_'.strtolower(DNS_ACCOUNT_CODE),
                "routing" => DNS_ACCOUNT_CODE,
                "filter"  => [
                    "term" => [
                        "tenant" => DNS_ACCOUNT_CODE
                    ]
                ]
            )
        )
    )
);
$client->indices()->updateAliases($params);

$params['body'] = array(
    'actions' => array(
        array(
            'add' => array(
                'index'   => 'au_part_isf',
                'alias'   => 'au_part_isf_'.strtolower(DNS_ACCOUNT_CODE),
                "filter"  => [
                    "term" => [
                        "tenant" => DNS_ACCOUNT_CODE
                    ]
                ]
            )
        )
    )
);
$client->indices()->updateAliases($params);


$params['body'] = array(
    'actions' => array(
        array(
            'add' => array(
                'index'   => 'au_part_location_isf',
                'alias'   => 'au_part_location_isf_'.strtolower(DNS_ACCOUNT_CODE),
                "filter"  => [
                    "term" => [
                        "tenant" => DNS_ACCOUNT_CODE
                    ]
                ]
            )
        )
    )
);
$client->indices()->updateAliases($params);

$params['body'] = array(
    'actions' => array(
        array(
            'add' => array(
                'index'   => 'au_warehouse_isf',
                'alias'   => 'au_warehouse_isf_'.strtolower(DNS_ACCOUNT_CODE),
                "filter"  => [
                    "term" => [
                        "tenant" => DNS_ACCOUNT_CODE
                    ]
                ]
            )
        )
    )
);
$client->indices()->updateAliases($params);