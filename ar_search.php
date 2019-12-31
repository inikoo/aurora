<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2015 at 18:35:53 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


use Elasticsearch\ClientBuilder;


require_once 'common.php';
require_once 'utils/ar_common.php';
//include_once 'search_functions.php';


$data = prepare_values(
    $_REQUEST, array(
                 'query' => array('type' => 'string'),
                 'state' => array('type' => 'json array')
             )
);


if ($data['query'] == '') {
    $response = array(
        'state'          => 200,
        'number_results' => 0,
        'results'        => array(),
        'query'          => ''

    );
    echo json_encode($response);
    exit;
}

if ($data['state']['current_store']) {
    $stores = array($data['state']['current_store']);
} else {
    $stores = $user->stores;
}


if ($user->get('User Type') == 'Agent') {
    agent_search($db, $account, $user, $data);
} else {
    if ($data['state']['module'] == 'customers' or $data['state']['module'] == 'customers_server') {

        $module = 'customers';


        if (count($stores) == 0) {
            $response = array(
                'state'          => 200,
                'number_results' => 0,
                'results'        => array(),
                'query'          => ''

            );
            echo json_encode($response);
            exit;
        }


        switch ($data['state']['section']) {
            case 'prospects':
            case 'prospect':
            case 'prospect.new':
            case 'prospects.email_template':
            case 'prospects.template.new':
                $scopes = array(
                    'prospects' => 10
                );
                break;
            default:
                $scopes = array(
                    'customers' => 10
                );
        }
        echo json_encode(search_ES(trim($data['query']), $module, $scopes, $stores));
        exit;


    } elseif ($data['state']['module'] == 'orders') {
        if ($data['state']['current_store']) {
            $data['scope']     = 'store';
            $data['scope_key'] = $data['state']['current_store'];
        } else {
            $data['scope'] = 'stores';
        }
        search_orders($db, $account, $user, $data);
    } elseif ($data['state']['module'] == 'products') {
        if ($data['state']['current_store']) {
            $data['scope']     = 'store';
            $data['scope_key'] = $data['state']['current_store'];
        } else {
            $data['scope'] = 'stores';
        }

        search_products($db, $account, $user, $data);


    } elseif ($data['state']['module'] == 'websites') {


        if ($data['state']['current_website']) {
            $data['scope']     = 'website';
            $data['scope_key'] = $data['state']['current_website'];
        } else {
            $data['scope'] = 'websites';
        }

        search_webpages($db, $account, $user, $data);

    } elseif ($data['state']['module'] == 'products_server') {

        $data['scope'] = 'stores';

        search_products($db, $account, $user, $data);
    } elseif ($data['state']['module'] == 'inventory') {
        if ($data['state']['current_warehouse']) {
            $data['scope']     = 'warehouse';
            $data['scope_key'] = $data['state']['current_warehouse'];
        } else {
            $data['scope'] = 'warehouses';
        }
        search_inventory($db, $account, $user, $data);
    } elseif ($data['state']['module'] == 'hr') {
        search_hr($db, $account, $user, $data);

    } elseif ($data['state']['module'] == 'suppliers') {
        search_suppliers($db, $account, $user, $data);

    } elseif ($data['state']['module'] == 'production') {
        search_production($db, $account, $user, $data);

    } elseif ($data['state']['module'] == 'delivery_notes') {
        if ($data['state']['current_store']) {
            $data['scope']     = 'store';
            $data['scope_key'] = $data['state']['current_store'];
        } else {
            $data['scope'] = 'stores';
        }
        search_delivery_notes($db, $account, $user, $data);
    } elseif ($data['state']['module'] == 'delivery_notes_server') {

        $data['scope'] = 'stores';

        search_delivery_notes($db, $account, $user, $data);
    } elseif ($data['state']['module'] == 'orders_server') {
        $data['scope'] = 'stores';
        search_orders($db, $account, $user, $data);
    } elseif ($data['state']['module'] == 'accounting_server') {
        if (in_array(
            $data['state']['section'], array(
            'deleted_invoice',
            'deleted_invoices_server',
            'invoices',
            'invoice',
            'category'
        )
        )) {


            $data['scope'] = 'stores';
            search_invoices($db, $account, $user, $data);
        } else {
            $data['scope'] = 'stores';
            search_payments($db, $account, $user, $data);
        }
    } elseif ($data['state']['module'] == 'accounting') {

        $data['scope']     = 'store';
        $data['scope_key'] = $data['state']['current_store'];
        if (in_array(
            $data['state']['section'], array(
            'deleted_invoice',
            'deleted_invoices',
            'invoices',
            'invoice',
            'category'
        )
        )) {
            search_invoices($db, $account, $user, $data);
        } else {
            search_payments($db, $account, $user, $data);
        }


    } elseif ($data['state']['module'] == 'warehouses') {
        if ($data['state']['current_warehouse']) {
            $data['scope']     = 'warehouse';
            $data['scope_key'] = $data['state']['current_warehouse'];
        } else {
            $data['scope'] = 'warehouses';
        }
        search_locations($db, $account, $user, $data);
    }
}


function search_ES($query, $module, $scopes, $stores) {


    $max_results = 16;


    $client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();


    $params = [
        'index' => strtolower('au_q_'.$_SESSION['account']),

        'body'    =>

            [
                "query" => [
                    "bool" => [
                        "must"   => [
                            [
                                "multi_match" => [
                                    "query" => $query,

                                    "type" => "bool_prefix",

                                    "fields" => [
                                        "rt",
                                        "rt._2gram",
                                        "rt._3gram"
                                    ]
                                ]
                            ]
                        ],
                        "filter" => [
                            [
                                "term" => [
                                    "module" => $module
                                ]
                            ]
                        ],
                        "should" => [
                            [
                                "rank_feature" => [
                                    "field" => "weight"
                                ]
                            ]

                        ]
                    ]
                ]
            ],
        '_source' => [
            'icon_classes',
            'label_1',
            'label_2',
            'label_3',
            'label_4',
            'url'
        ],
        'size'    => $max_results


    ];


    foreach ($scopes as $scope => $boost) {
        $params['body']['query']['bool']['should'][] = array(
            "rank_feature" => [
                "field" => "scopes.".$scope,
                "boost" => $boost
            ]
        );
    }

    foreach ($stores as $store) {
        $params['body']['query']['bool']['filter'][] = array(
            "term" => [
                "store_key" => $store
            ]
        );
    }


    $result = $client->search($params);


    return array(
        'state'          => 200,
        'number_results' => $result['hits']['total']['value'],
        'results'        => $result['hits']['hits'],
        'query'          => $query,
        'class'          => $module

    );


}

