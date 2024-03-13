<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   06 April 2020  16:12::43  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require '../../keyring/dns.php';
require '../../keyring/au_deploy_conf.php';

use Elasticsearch\ClientBuilder;

require '../../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
    ->setApiKey(ES_KEY1,ES_KEY2)
    ->setSSLVerification(ES_SSL)
    ->build();



$params = [
    'index' => strtolower('au_web_search'),
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
                'content' => array(
                    'type'  => 'text'
                ),
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


                'status'      => array(
                    'type' => 'keyword',
                ),
                'preview_class'      => array(
                    'type' => 'text',
                    'index' => false
                ),
                'preview_image'      => array(
                    'type' => 'text',
                    'index' => false
                ),
                'preview_title'      => array(
                    'type' => 'text',
                    'index' => false
                ),
                'preview_text'      => array(
                    'type' => 'text',
                    'index' => false
                ),

                'store_key'   => array(
                    'type' => 'short',

                ),
                'website_key'   => array(
                    'type' => 'short',

                ),
                'webpage_key'   => array(
                    'type' => 'short',
                    'index' => false

                ),
                'scope' => array(
                    'type'  => 'keyword',
                    'index' => false
                ),
                'scope_key' => array(
                    'type'  => 'short',
                    'index' => false
                ),


                'weight'      => array(
                    'type' => 'rank_feature',
                ),
                'scopes'      => array(
                    'type' => 'rank_features',
                ),


            )
        )

    ),

];

$response = $client->indices()->create($params);

