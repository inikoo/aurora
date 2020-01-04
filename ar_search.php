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


        check_for_store_permissions($stores);

        if (in_array(
            $data['state']['section'], array(
                                         'prospects',
                                         'prospect',
                                         'prospect.new',
                                         'prospects.email_template',
                                         'prospects.template.new'
                                     )
        )) {
            $scopes = array(
                'prospects' => 10
            );
        } else {
            $scopes = array(
                'customers' => 10
            );
        }


        echo json_encode(search_ES(trim($data['query']), 'customers', $scopes, $stores));
        exit;


    } elseif ($data['state']['module'] == 'orders' or $data['state']['module'] == 'orders_server') {

        check_for_store_permissions($stores);
        echo json_encode(search_ES(trim($data['query']), 'orders', array(), $stores));
        exit;

    } elseif ($data['state']['module'] == 'products' or $data['state']['module'] == 'products_server') {


        check_for_store_permissions($stores);
        echo json_encode(search_ES(trim($data['query']), 'products', array(), $stores));
        exit;


    } elseif ($data['state']['module'] == 'inventory') {

        echo json_encode(search_ES(trim($data['query']), 'inventory', array(), array()));
        exit;
    } elseif ($data['state']['module'] == 'websites') {


        check_for_store_permissions($stores);
        echo json_encode(search_ES(trim($data['query']), 'websites', array(), $stores));
        exit;

    } elseif ($data['state']['module'] == 'hr') {
        echo json_encode(search_ES(trim($data['query']), 'hr'));
        exit;

    } elseif ($data['state']['module'] == 'users') {
        echo json_encode(search_ES(trim($data['query']), 'users'));
        exit;

    } elseif ($data['state']['module'] == 'suppliers') {

        if (in_array(
            $data['state']['section'], array(
                                         'agents',
                                         'agent',
                                         'agents.new'
                                     )
        )) {
            $scopes = array(
                'agents' => 10
            );
        } else {
            $scopes = array(
                'suppliers' => 10
            );
        }

        echo json_encode(search_ES(trim($data['query']), 'suppliers'));
        exit;

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
    } elseif ($data['state']['module'] == 'accounting' or data['state']['module'] == 'accounting_server') {


        if (in_array(
            $data['state']['section'], array(

                                         'invoices',
                                         'invoice',
                                         'category'
                                     )
        )) {
            $scopes = array(
                'invoices' => 10
            );
        } elseif (in_array(
            $data['state']['section'], array(
                                         'deleted_invoice',
                                         'deleted_invoices',
                                         'deleted_invoices_server',

                                     )
        )) {
            $scopes = array(
                'deleted_invoices' => 10,

            );
        } elseif (in_array(
            $data['state']['section'], array(
                                         'payments',
                                         'credits',
                                         'payment_account',
                                         'payment'

                                     )
        )) {
            $scopes = array(
                'payments' => 10
            );
        } else {
            $scopes = array(
                'invoices'         => 10,
                'payments'         => 7,
                'deleted_invoices' => 2
            );
        }
        check_for_store_permissions($stores);
        echo json_encode(search_ES(trim($data['query']), 'accounting', $scopes, $stores));

    } elseif ($data['state']['module'] == 'warehouses') {

        echo json_encode(search_ES(trim($data['query']), 'warehouse'));
        exit;
    }
}

function check_for_store_permissions($stores) {
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
}

function search_ES($query, $module, $scopes = array(), $stores = array()) {


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

