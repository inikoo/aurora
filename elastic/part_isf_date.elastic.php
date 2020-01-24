<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 January 2020  00:40::00  +0800 Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

use Elasticsearch\ClientBuilder;


function get_elastic_stock_history_day($_data) {




    $client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

    $params = [
        'index' => strtolower('au_part_isf_'.strtolower(DNS_ACCOUNT_CODE)),

        'body' => [
            "sort" => [
                [
                    $_data['o'] => [
                        "order" => $_data['od']
                    ]
                ]
            ],


        ],


        'from' => $_data['nr'] * ($_data['page'] - 1),
        'size' => $_data['nr']
    ];


    $params['body']['query'] = [

        'bool' => [


            'filter' => [
                "term" => [
                    $_data['parameters']['parent'] => [
                        'value' => $_data['parameters']['parent_key'],
                    ]
                ]
            ]
        ]


    ];

    if (  !empty($_data['f_field']) and !empty($_data['f_value']) ) {

        $params['body']['query']['bool']['must'] = [

                'prefix' => [
                    $_data['f_field'] => ['value' => $_data['f_value']]
                ]


        ];

    }




    $raw_result = $client->search($params);


    return $raw_result['hits'];

}


