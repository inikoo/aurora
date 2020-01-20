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

//curl -X DELETE 'http://localhost:9200/au_wisf';


$params = [
    'index' => strtolower('au_wisf'),
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
                '1st_day_year' => array(
                    'type'=> 'boolean',
                ),
                '1st_day_month' => array(
                    'type'=> 'boolean',
                ),
                '1st_day_quarter' => array(
                    'type'=> 'boolean',
                ),
                '1st_day_week' => array(
                    'type'=> 'boolean',
                ),


                'parts'  => [
                    'type'       => 'short',
                ],
                'locations'  => [
                    'type'       => 'short',
                ],

                'stock_cost'  => [
                    'type'       => 'float',
                ],
                'stock_value_at_day_cost'  => [
                    'type'       => 'float',
                ],
                'stock_commercial_value'  => [
                    'type'       => 'float',
                ],

                'stock_value_in_purchase_order'  => [
                    'type'       => 'float',
                ],
                'stock_value_in_other'  => [
                    'type'       => 'float',
                ],
                'stock_value_out_sales'  => [
                    'type'       => 'float',
                ],
                'stock_value_out_other'  => [
                    'type'       => 'float',
                ],
                'stock_value_dormant_1y'  => [
                    'type'       => 'float',
                ],
                'parts_with_no_sales_1y'  => [
                    'type'       => 'short',
                ],
                'parts_with_stock_left_1y'  => [
                    'type'       => 'short',
                ],


            )
        )

    ),

];
$response = $client->indices()->create($params);
print_r($response);