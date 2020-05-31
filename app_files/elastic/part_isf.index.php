<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:    13 January 2020  11:44::58  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/

require '../../keyring/dns.php';
require '../../keyring/au_deploy_conf.php';

use Elasticsearch\ClientBuilder;

require '../../vendor/autoload.php';

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();


$params   = [
    'index' => strtolower('au_part_isf'),
    'body'  => array(
        'settings' => array(
            'analysis' => array(
                'analyzer'   => [
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
                'tenant'          => [
                    'type'       => 'keyword',
                    "normalizer" => "code_normalizer"
                ],

                'date'            => array(
                    'type'   => 'date',
                    "format" => "yyyy-MM-dd"

                ),
                '1st_day_year'    => array(
                    'type' => 'boolean',
                ),
                '1st_day_month'   => array(
                    'type' => 'boolean',
                ),
                '1st_day_quarter' => array(
                    'type' => 'boolean',
                ),
                '1st_day_week'    => array(
                    'type' => 'boolean',
                ),
                'sku'             => [
                    'type' => 'keyword',
                ],



                'location' => [
                    'type'  => 'keyword',
                ],
                'warehouse' => [
                    'type'  => 'keyword',
                ],

                'stock_on_hand' => [
                    'type'  => 'float',
                    'index' => false
                ],

                'stock_cost'              => [
                    'type'  => 'float',
                ],
                'stock_value_at_day_cost' => [
                    'type'  => 'float',
                ],
                'stock_commercial_value'  => [
                    'type'  => 'float',
                    'index' => false
                ],

                'stock_value_in_purchase_order' => [
                    'type'  => 'float',
                    'index' => false
                ],
                'stock_value_in_other'          => [
                    'type'  => 'float',
                    'index' => false
                ],
                'stock_value_out_sales'         => [
                    'type'  => 'float',
                    'index' => false
                ],
                'stock_value_out_other'         => [
                    'type'  => 'float',
                    'index' => false
                ],



                'book_in'               => [
                    'type'  => 'float',
                    'index' => false
                ],
                'sold'                  => [
                    'type'  => 'float',
                    'index' => false
                ],
                'sold_amount'                  => [
                    'type'  => 'float',
                    'index' => false
                ],
                'given'                 => [
                    'type'  => 'float',
                    'index' => false
                ],
                'lost'                  => [
                    'type'  => 'float',
                    'index' => false
                ],
                'sko_cost'                  => [
                    'type'  => 'keyword',
                    'index' => false
                ],
                'stock_left_1_year_ago' => [
                    'type'  => 'float',

                ],
                'no_sales_1_year'       => [
                    'type' => 'boolean'

                ],
                'part_description'      => [
                    'type'  => 'text',
                    'index' => false
                ],
                'part_description'      => [
                    'type'  => 'text',
                    'index' => false
                ],
                'no_sales_1_year_icon'  => [
                    'type'  => 'keyword',
                    'index' => false
                ],

                'part_reference'        => [
                    'type'           => 'keyword',
                    "normalizer" => "code_normalizer",

                    "fields"=>[
                        'text'=>[
                            'type'=>'text',
                            'index_prefixes' => new \stdClass(),
                            "analyzer" => "rt_code",


                        ]
                    ]

                ],

            )
        )

    ),

];
$response = $client->indices()->create($params);


print_r($response);


