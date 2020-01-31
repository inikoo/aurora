<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  18 January 2020  21:37::07  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

use Elasticsearch\ClientBuilder;


function get_part_inventory_transaction_fact($scope, $part_sku, $calendar_interval = '1d', $_order='asc', $timezone = 'UTC') {

    $client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();


    switch ($scope) {

        case 'stock':
            $aggregations = [
                "stock" => [
                    "sum" => [
                        "field" => "stock_on_hand"
                    ]
                ]


            ];



            break;
        case 'sales':
            $aggregations = [

                "sold"        => [
                    "sum" => [
                        "field" => "sold"
                    ]
                ],
                "sold_amount" => [
                    "sum" => [
                        "field" => "sold_amount"
                    ]
                ],


            ];

            break;
        default:
            $aggregations = [
                "stock"                   => [
                    "sum" => [
                        "field" => "stock_on_hand"
                    ]
                ],
                "stock_cost"              => [
                    "sum" => [
                        "field" => "stock_cost"
                    ]
                ],
                "stock_value_at_day_cost" => [
                    "sum" => [
                        "field" => "stock_value_at_day_cost"
                    ]
                ],
                "commercial_value"        => [
                    "sum" => [
                        "field" => "stock_commercial_value"
                    ]
                ],
                "book_in"                 => [
                    "sum" => [
                        "field" => "book_in"
                    ]
                ],
                "sold"                    => [
                    "sum" => [
                        "field" => "sold"
                    ]
                ],
                "sold_amount"             => [
                    "sum" => [
                        "field" => "sold_amount"
                    ]
                ],
                "lost"                    => [
                    "sum" => [
                        "field" => "lost"
                    ]
                ],


            ];
           // $_order='desc';
    }


    $params = [
        'index' => strtolower('au_part_isf_'.strtolower(DNS_ACCOUNT_CODE)),

        'body' => [
            "sort" => [
                [
                    "date" => [
                        "order" => "desc"
                    ]
                ]
            ],
            "aggs" => [
                "stock_per_day" => [
                    "date_histogram"    => [
                        "field"             => "date",
                        "calendar_interval" => $calendar_interval,
                        "time_zone"         => $timezone,
                        "min_doc_count"     => 1,
                        "order" =>[ "_key" => $_order ]

                    ],
                    "aggregations"      => $aggregations,

                ]
            ],


        ],


        'size' => 0
    ];

    if ($part_sku > 0) {
        $params['body']['query'] = [
            'bool' => [
                'must'   => [],
                'filter' => [

                    [
                        "term" => [
                            'sku' => $part_sku,
                        ],


                    ],

                ]
            ]
        ];
    }


    $result = $client->search($params);


    return $result;

}

