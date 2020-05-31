<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require '../../keyring/dns.php';
require '../../keyring/au_deploy_conf.php';

use Elasticsearch\ClientBuilder;

require '../../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

//curl -X DELETE 'http://localhost:9200/au_search_es';
//curl -X DELETE 'http://localhost:9200/au_search_aw';
//curl -X DELETE 'http://localhost:9200/au_search_aweu';


$params = [
    'index' => strtolower('au_search'),
    'body'  => array(
        'settings' => array(
            'analysis' => array(
                'analyzer'   => [
                    'icu_analyzer',
                    'rt_code' => [
                        'tokenizer' => 'whitespace',
                        "filter"    => [
                            "lowercase",
                            "asciifolding"
                        ]
                    ]
                ],
                "normalizer" => [
                    "code_normalizer" => [
                        "type"        => "custom",
                        "char_filter" => [],
                        "filter"      => [
                            "lowercase",
                            "asciifolding"
                        ]
                    ]
                ]

            ),


        ),
        'mappings' => array(

            'properties' => array(
                'tenant'  => [
                    'type'       => 'keyword',
                    "normalizer" => "code_normalizer"
                ],
                'rt'      => array(
                    'type' => 'search_as_you_type'
                ),
                'rt_code' => array(
                    'type'     => 'search_as_you_type',
                    "analyzer" => "rt_code"
                ),

                'url' => array(
                    'type'  => 'keyword',
                    'index' => false
                ),

                'code'   => array(
                    'type'       => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'module' => array(
                    'type' => 'keyword',

                ),
                'icon_classes' => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'label_1'      => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'label_2'      => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'label_3'      => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'label_4'      => array(
                    'type'  => 'text',
                    'index' => false
                ),

                'store_label' => array(
                    'type'  => 'text',
                    'index' => false
                ),
                'store_key'   => array(
                    'type' => 'short',

                ),
                'weight'      => array(
                    'type' => 'rank_feature',
                ),
                'scopes'      => array(
                    'type' => 'rank_features',
                ),

                'agent_user' => array(
                    'type'  => 'keyword',
                ),
                'supplier_user' => array(
                    'type'  => 'keyword',
                ),
                'customer_user' => array(
                    'type'  => 'keyword',
                ),
            )
        )

    ),

];

$response = $client->indices()->create($params);

