<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  18 January 2020  21:37::07  +0800, Kuala Lumpur, Malaysia

 Copyright (c) 2020, Inikoo

 Version 2.0
*/
use Elasticsearch\ClientBuilder;


function get_elastic_sales_correlated_assets($object_key,$scope_prefix,$period_suffix='',$number_results=20,$min_doc_count=1){

    $client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();
    $params = [
        'index' => strtolower('au_customers_'.strtolower(DNS_ACCOUNT_CODE)),

        'body' =>
            [
                "query"        => [
                    'term' => [
                        $scope_prefix.$period_suffix => $object_key
                    ]
                ],
                'aggregations' => [
                    'assets' => [
                        'significant_terms' => [
                            "field"         => $scope_prefix.$period_suffix,
                            "min_doc_count" => $min_doc_count,
                            "size"          =>$number_results
                        ]
                    ]
                ]
            ],
        '_source' => [
            'store_key',
        ],
        'size'    => 1
    ];

    $result= $client->search($params);
    return $result['aggregations']['assets'];

}