<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01 December 2019  17:34::02  +0100, Mijas Costa, Spain

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require '../../keyring/dns.php';

use Elasticsearch\ClientBuilder;

require '../../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();






$params = [
    'index' => strtolower('au_web_search_analytics'),
    'body'  => array(
        'settings'=>array(
            'analysis'=>array(
                'analyzer'=>'icu_analyzer',
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
            )
        ),
        'mappings' => array(

            'properties' => array(
                'tenant'  => [
                    'type'       => 'keyword',
                    "normalizer" => "code_normalizer"
                ],
                'date' => array(
                    'type'=> 'date',
                    "format"=> "yyyy-MM-dd'T'HH:mm:ss.SSSSSS"

                ),

                'search_index' => array(
                    'type'  => 'keyword',

                ),



                'stores' => array(
                    'type'  => 'keyword',

                ),
                'query' => array(
                    'type'=> 'text'
                ),
                'modules'          => array(
                    'type'  => 'keyword',

                ),
                'section'       => array(
                    'type'  => 'keyword',
                ),
                'customer'       => array(
                    'type'  => 'keyword',
                ),


                'action' => array(
                    'type'  => 'keyword',

                ),

                'click_url' => array(
                    'type'  => 'keyword',

                ),
                'click_pos' => array(
                    'type'  => 'byte',

                ),
                'delta_time' => array(
                    'type'  => 'half_float',

                ),

                'number_results' => array(
                    'type'  => 'integer',

                ),
            )
        )

    ),

];

$response = $client->indices()->create($params);


