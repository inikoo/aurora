<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2015 at 18:35:53 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


use Elasticsearch\ClientBuilder;


require_once 'utils/ar_common.php';


$data = prepare_values(
    $_REQUEST, array(
                 'query'        => array('type' => 'string'),
                 'search_index' => array('type' => 'string'),
                 'mtime'        => array('type' => 'string'),
                 'state'        => array('type' => 'json array')
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

if (!empty($data['state']['current_store'])) {
    $stores = array($data['state']['current_store']);
} else {
    $stores = $user->stores;
}


if ($user->get('User Type') == 'Agent') {




    if ($data['state']['module'] == 'agent_suppliers' ) {
        $scopes = array(
            'suppliers' => 10
        );
        $module=['suppliers'];
    }elseif ($data['state']['module'] == 'agent_parts' or $data['state']['module'] == 'agent_part' ) {
        $scopes = array(
            'supplier_parts' => 10
        );
        $module=['suppliers'];
    }else{
        $module=['suppliers'];
        $scopes=[];
    }
    echo json_encode(search_ES($data, $user->get('Handle'), $module,$scopes,[],[$user->get('User Parent Key')]  ));


    exit;



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
        } elseif (in_array(
            $data['state']['section'], array(
                                         'lists',
                                         'list',
                                         'list.new',

                                     )
        )) {
            $scopes = array(
                'lists' => 10
            );
        } else {
            $scopes = array(
                'customers' => 10
            );
        }


        echo json_encode(search_ES($data, $user->get('Handle'), ['customers'], $scopes, $stores));
        exit;


    } elseif ($data['state']['module'] == 'orders' or $data['state']['module'] == 'orders_server') {

        check_for_store_permissions($stores);
        echo json_encode(search_ES($data, $user->get('Handle'), ['orders'], array(), $stores));
        exit;

    } elseif ($data['state']['module'] == 'products' or $data['state']['module'] == 'products_server') {


        check_for_store_permissions($stores);
        echo json_encode(search_ES($data, $user->get('Handle'), ['products'], array(), $stores));
        exit;


    } elseif ($data['state']['module'] == 'mailroom' or $data['state']['module'] == 'mailroom_server') {


        check_for_store_permissions($stores);
        echo json_encode(search_ES($data, $user->get('Handle'), ['mailroom'], array(), $stores));
        exit;


    } elseif ($data['state']['module'] == 'offers' or $data['state']['module'] == ' offers_server') {


        check_for_store_permissions($stores);
        echo json_encode(search_ES($data, $user->get('Handle'), ['offers'], array(), $stores));
        exit;


    } elseif ($data['state']['module'] == 'inventory') {

        echo json_encode(search_ES($data, $user->get('Handle'), ['inventory'], array(), array()));
        exit;
    } elseif ($data['state']['module'] == 'websites') {


        check_for_store_permissions($stores);
        echo json_encode(search_ES($data, $user->get('Handle'), ['websites'], array(), $stores));
        exit;

    } elseif ($data['state']['module'] == 'hr') {
        echo json_encode(search_ES($data, $user->get('Handle'), ['hr']));
        exit;

    } elseif ($data['state']['module'] == 'users') {
        echo json_encode(search_ES($data, $user->get('Handle'), ['users']));
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

        echo json_encode(search_ES($data, $user->get('Handle'), ['suppliers']));
        exit;

    } elseif ($data['state']['module'] == 'production') {
        echo json_encode(search_ES($data, $user->get('Handle'), ['production']));
        exit;

    } elseif ($data['state']['module'] == 'delivery_notes' or $data['state']['module'] == 'delivery_notes_server') {
        check_for_store_permissions($stores);
        echo json_encode(search_ES($data, $user->get('Handle'), ['delivering'], array(), $stores));
        exit;
    } elseif ($data['state']['module'] == 'accounting' or $data['state']['module'] == 'accounting_server') {


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
        echo json_encode(search_ES($data, $user->get('Handle'), ['accounting'], $scopes, $stores));

    } elseif ($data['state']['module'] == 'warehouses') {

        echo json_encode(search_ES($data, $user->get('Handle'), ['warehouse']));
        exit;
    } elseif ($data['state']['module'] == 'dashboard') {
        echo json_encode(search_ES($data, $user->get('Handle'), [], array(), $stores));
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

function search_ES($query_data, $user_code, $modules, $scopes = [], $stores = [],$agents=[]) {





    $section = $query_data['state']['section'];


    $query = trim($query_data['query']);


    $max_results = 16;


    $client = ClientBuilder::create()->setHosts(get_elasticsearch_hosts())
        ->setApiKey(ES_KEY1,ES_KEY2)
        ->setSSLVerification(ES_SSL)
        ->build();


    if (in_array('customers', $modules)) {


        if (preg_match_all(
            '/([A-Z]{1,2}\d[A-Z\d]?\s\d[A-Z]{2})/i', $query, $matches
        )) {

            foreach ($matches[1] as $match) {
                $query .= ' '.preg_replace('/\s/', '', $match);
            }


        }


    }


    $params = [
        'index' => strtolower('au_search_'.strtolower(DNS_ACCOUNT_CODE)),

        'body'    =>

            [
                "query" => [
                    "bool" => [
                        "must" => [
                            [
                                "multi_match" => [
                                    "query" => $query,

                                    "type" => "bool_prefix",

                                    "fields" => [
                                        "rt",
                                        "rt._2gram",
                                        "rt._3gram",
                                        "rt_code",
                                        "rt_code._2gram",
                                        "rt_code._3gram"
                                    ]
                                ]
                            ]
                        ],

                        "should" => [
                            [
                                "rank_feature" => [
                                    "field" => "weight",
                                    "factor" => 1.2,
                                    "modifier" => "log1p"
                                ]
                            ],
                            [
                                'match' => [
                                    'code^2' => $query,

                                ]
                            ],
                            [
                                "multi_match" => [
                                    "query" => $query,

                                    "type" => "bool_prefix",

                                    "fields" => [
                                        "rt_code^1.1",
                                        "rt_code._2gram^1.1",
                                        "rt_code._3gram^1.1"
                                    ]

                                ]
                            ]

                        ]
                    ]
                ]
            ],
        '_source' => [
            'icon_classes',
            'store_label',
            'label_1',
            'label_2',
            'label_3',
            'label_4',
            'url'
        ],
        'size'    => $max_results


    ];




    if (count($modules) > 0) {

        $params['body']['query']['bool']['filter'][] = array(
            "terms" => [
                "module" => $modules
            ]
        );


    }

    foreach ($scopes as $scope => $boost) {
        $params['body']['query']['bool']['should'][] = array(
            "rank_feature" => [
                "field" => "scopes.".$scope,
                "boost" => $boost,
                "factor" => 1.2,
                "modifier" => "log1p"
            ]
        );
    }

    if (count($stores) > 0) {
        $params['body']['query']['bool']['filter'][] = array(
            "terms" => [
                "store_key" => $stores
            ]
        );
    }

    if (count($agents) > 0) {
        $params['body']['query']['bool']['filter'][] = array(
            "terms" => [
                "agent_user" => $agents
            ]
        );
    }


    $now = DateTime::createFromFormat('U.u', sprintf('%.f', microtime(true)));


//    print_r($params);
//    exit;

    $result = $client->search($params);

    $mtime = $now->format("U.u");


    if ($query_data['search_index'] == '') {
        $action    = 'seed';
        $time_diff = '';
    } else {
        $action    = 'searching';
        $time_diff = $mtime - $query_data['mtime'];

        /*
        if ($time_diff < .400) {
            $status = 'typing';

        } else {
            $status = 'searching';

        }
        */
    }


    $analytics_params = [
        'index' => strtolower('au_q_search_analytics_'.strtolower(DNS_ACCOUNT_CODE)),
        'body'  => [
            'date'           => $now->format("Y-m-d\TH:i:s.u"),
            'tenant'         => strtolower(DNS_ACCOUNT_CODE),
            'stores'         => join($stores),
            'query'          => $query_data['query'],
            'modules'        => (count($modules) > 0 ? $modules[0] : ''),
            'section'        => $section,
            'user'           => $user_code,
            'search_index'   => $query_data['search_index'],
            'action'         => $action,
            'delta_time'     => $time_diff,
            'number_results' => $result['hits']['total']['value']

        ]
    ];



    $analytics_index = $client->index($analytics_params);


    $class = (count($modules) > 0 ? $modules[0] : 'dashboard');


    if (preg_match('/_server/', $query_data['state']['module'])) {


        $class .= ' server';
    }

    return array(
        'state'          => 200,
        'number_results' => $result['hits']['total']['value'],
        'results'        => $result['hits']['hits'],
        'query'          => $query_data['query'],
        'class'          => $class,
        'search_index'   => $analytics_index['_id'],
        'mtime'          => $mtime

    );


}

