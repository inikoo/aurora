<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:    13 January 2020  11:44::58  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require '../../keyring/dns.php';

use Elasticsearch\ClientBuilder;

require '../../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

//curl -X DELETE 'http://localhost:9200/au_isf';


$params = [
    'index' => strtolower('au_isf'),
    'body'  => array(
        'settings' => array(
            'analysis'   => array(
                'analyzer' => [
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
                'warehouse'  => [
                    'type'       => 'keyword',
                    "normalizer" => "code_normalizer"
                ],
                'date' => array(
                    'type'=> 'date',
                    "format"=> "yyyy-MM-dd"

                ),
                'sku'  => [
                    'type'       => 'integer',
                ],
                'location_key'  => [
                    'type'       => 'short',
                ],
                'part'  => [
                    'type'       => 'keyword',
                    "normalizer" => "code_normalizer"
                ],
                'location'  => [
                    'type'       => 'keyword',
                    "normalizer" => "code_normalizer"
                ],

                'qty'  => [
                    'type'       => 'float',
                ],
                'cost_paid'  => [
                    'type'       => 'float',
                ],
                'cost_current'  => [
                    'type'       => 'float',
                ],
                'value'  => [
                    'type'       => 'float',
                ],



            )
        )

    ),

];
$response = $client->indices()->create($params);
print_r($response);