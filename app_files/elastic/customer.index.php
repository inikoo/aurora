<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   11 January 2020  12:26::41  +0800, Kuala Lumpur, Malaysia

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
//curl -X DELETE 'http://localhost:9200/au_customers';


$params = [
    'index' => strtolower('au_customers'),
    'body'  => array(
        'settings' => array(
            'analysis'   => array(
                'analyzer' => [
                    'icu_analyzer',
                    'rt_code'=>[
                        'tokenizer'=>'whitespace',
                        "filter"      => [
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
                'code'  => [
                    'type'       => 'keyword',
                    "normalizer" => "code_normalizer"
                ],

                'url'    => array(
                    'type'  => 'keyword',
                    'index' => false
                ),
                'favourites'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),


                'products_bought'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'families_bought'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'departments_bought'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),

                'products_bought_1y'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'families_bought_1y'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'departments_bought_1y'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),

                'products_bought_1q'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'families_bought_1q'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'departments_bought_1q'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),

                'products_bought_1m'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'families_bought_1m'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'departments_bought_1m'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),

                'products_bought_1w'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'families_bought_1w'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),
                'departments_bought_1w'    => array(
                    'type'  => 'keyword',
                    "normalizer" => "code_normalizer"
                ),

                'history'      => array(
                    'type' => 'text'
                ),

                'label'      => array(
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
            )
        )

    ),

];
$response = $client->indices()->create($params);
print_r($response);