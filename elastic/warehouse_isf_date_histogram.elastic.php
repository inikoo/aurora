<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  18 January 2020  21:37::07  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/

use Elasticsearch\ClientBuilder;


function get_warehouse_isf($_data) {

    if ($_data['parameters']['frequency'] == 'annually') {
        $calendar_interval = '1y';
        $interval_term     = '1st_day_year';

    } elseif ($_data['parameters']['frequency'] == 'monthly') {
        $calendar_interval = '1M';
        $interval_term     = '1st_day_month';

    } elseif ($_data['parameters']['frequency'] == 'quarterly') {
        $calendar_interval = '1q';

        $interval_term = '1st_day_quarter';

    } elseif ($_data['parameters']['frequency'] == 'weekly') {
        $calendar_interval = '1w';
        $interval_term     = '1st_day_week';


    } else {
        $calendar_interval = '1d';
        $interval_term     = '';

    }


    $client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

    $params = [
        'index' => strtolower('au_warehouse_isf_'.strtolower(DNS_ACCOUNT_CODE)),

        'body' => [
            "sort" => [
                [
                    $_data['o'] => [
                        "order" => $_data['od']
                    ]
                ]
            ],


        ],


        'from' => $_data['nr']*($_data['page'] - 1),
        'size' => $_data['nr']
    ];


    if ($interval_term != '') {
        $params['body']['query'] = [
            "term" => [
                $interval_term => [
                    'value' => true,
                ]
            ]
        ];
    }


    $raw_result = $client->search($params);


    return $raw_result['hits'];

}