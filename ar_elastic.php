<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2016 at 10:37:32 GMT+8, Yuwu, China
 Copyright (c) 20156 Inikoo

 Version 3

*/

use Elasticsearch\ClientBuilder;

require_once 'common.php';
require_once 'utils/ar_common.php';


if (!isset($_REQUEST['args']['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();


$tipo = $_REQUEST['args']['tipo'];

switch ($tipo) {


    case 'correlated_products':
        $data = prepare_values(
            $_REQUEST['args'], array(
                                 'args'   => array('type' => 'string'),
                                 'period' => array('type' => 'string'),
                             )
        );
        correlated_products($data, $db, $client);

        break;

    case 'correlated_products_excl_family':
        $data = prepare_values(
            $_REQUEST['args'], array(
                                 'args'   => array('type' => 'string'),
                                 'period' => array('type' => 'string'),
                             )
        );
        correlated_products_excl_family($data, $db, $client);

        break;
    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}

function correlated_products($_data, $db, $client) {


//    $_SESSION['island_state']['correlations']['period'] = 'all';



    if(isset($_data['period']) and in_array($_data['period'],['all','1y','1q']) ){
        $period=$_data['period'];
    }else{
        $period='all';
    }

    $_SESSION['island_state']['correlations']['period'] = $period;

    switch ($period) {
        case 'all':
            $period_suffix = '';
            break;

        default:
            $period_suffix = '_'.$period;
            break;
    }





    $max_results = 20;

    $params = [
        'index' => strtolower('au_customers_'.strtolower(DNS_ACCOUNT_CODE)),

        'body' =>

            [

                "query"        => [
                    'match' => [
                        'products_bought'.$period_suffix  => $_data['args']['key']
                    ]
                ],
                'aggregations' => [
                    'products' => [
                        'significant_terms' => [
                            "field"         => "products_bought".$period_suffix ,
                            "min_doc_count" => 1,
                            "size"          => $_data['args']['size']
                        ]
                    ]
                ]
            ],

        '_source' => [
            'store_key',

            'url'
        ],
        'size'    => 1


    ];


    $result = $client->search($params);

    $assets_ids  = [];
    $assets_data = [];
    foreach ($result['aggregations']['products']['buckets'] as $result) {
        if ($result['key'] == $_data['args']['key']) {
            continue;
        }
        $assets_ids[]                = $result['key'];
        $assets_data[$result['key']] = [
            'score' => $result['score']
        ];

    }

    $in   = str_repeat('?,', count($assets_ids) - 1).'?';
    $sql  =
        "SELECT `Product Family Category Key`,`Product Availability State`,`Product ID`,`Product Code`,`Product Name`,`Product Units Per Case`,`Product Status`,`Product Web State`,`Product Web Configuration`,`Product Availability`,`Product Number of Parts` FROM `Product Dimension` WHERE `Product ID` IN ($in)";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        $assets_ids
    );


    while ($row = $stmt->fetch()) {

        switch ($row['Product Status']) {
            case 'Discontinuing':
                $icon_classes = 'fa fa-fw fa-cube warning';
                break;
            case 'Discontinued':

                $icon_classes = 'fa fa-fw fa-cube very_discreet';
                break;
            case 'Suspended':

                $icon_classes = 'fa fa-fw fa-cube error';
                break;
            default:

                $icon_classes = 'fa fa-fw fa-cube';
                break;
        }
        switch ($row['Product Web Configuration']) {
            case 'Online Force Out of Stock':
                $icon_classes .= '|fa fa-fw fa-stop red';
                break;

            case 'Online Force For Sale':
                $icon_classes .= '|fa fa-fw fa-stop';

                switch ($row['Product Availability State']) {
                    case 'OnDemand':
                    case 'Normal':
                    case 'Excess':
                        $icon_classes .= ' green';
                        break;
                    case 'VeryLow':
                    case 'Error':
                    case 'OutofStock':
                    case 'Low':
                        $icon_classes .= ' yellow';
                        break;
                    default:
                        break;
                }

                break;
            case 'Online Auto':
                $icon_classes .= '|fa fa-fw fa-circle';

                switch ($row['Product Availability State']) {
                    case 'OnDemand':
                    case 'Normal':
                    case 'Excess':
                        $icon_classes .= ' green';
                        break;
                    case 'VeryLow':
                    case 'Low':
                        $icon_classes .= ' yellow';
                        break;
                    case 'Error':
                    case 'OutofStock':
                        $icon_classes .= ' red';
                        break;
                    default:
                        break;
                }

                break;

        }
        $icons = '';
        foreach (preg_split('/\|/', $icon_classes) as $icon_class) {
            $icons .= "<i class='$icon_class'></i> ";
        }

        $code = $row['Product Code'];
        $name = $row['Product Units Per Case'].'x '.$row['Product Name'];

        $assets_data[$row['Product ID']]['data'] = [
            $icons,
            $code,
            $name
        ];

    }

    $html        = '';
    $assets_data = array_slice($assets_data, 0, $max_results);
    foreach ($assets_data as $asset_data) {
        $html .= '<tr>
                <td>'.$asset_data['data'][0].'</td><td>'.$asset_data['data'][1].'</td><td>'.$asset_data['data'][2].'</td>
                </tr>';

    }


    echo json_encode(
        ['html' => $html]
    );

}


function correlated_products_excl_family($_data, $db, $client) {

    $max_results = 20;


    if(isset($_data['period']) and in_array($_data['period'],['all','1y','1q']) ){
        $period=$_data['period'];
    }else{
        $period='all';
    }

    $_SESSION['island_state']['correlations']['period'] = $period;

    switch ($period) {
        case 'all':
            $period_suffix = '';
            break;

        default:
            $period_suffix = '_'.$period;
            break;
    }




    $params = [
        'index' => strtolower('au_customers_'.strtolower(DNS_ACCOUNT_CODE)),

        'body' =>

            [

                "query"        => [
                    'match' => [
                        'products_bought'.$period_suffix  => $_data['args']['key']
                    ]
                ],
                'aggregations' => [
                    'products' => [
                        'significant_terms' => [
                            "field"         => "products_bought".$period_suffix ,
                            "min_doc_count" => 1,
                            "size"          => $_data['args']['size']
                        ]
                    ]
                ]
            ],

        '_source' => [
            'store_key',

            'url'
        ],
        'size'    => 1


    ];

    print_r($params);

    $result = $client->search($params);

    print_r($result);

    $assets_ids  = [];
    $assets_data = [];
    foreach ($result['aggregations']['products']['buckets'] as $result) {
        if ($result['key'] == $_data['args']['key']) {
            continue;
        }
        $assets_ids[]                         = $result['key'];
        $assets_data[$result['key']]          = [
            'score' => $result['score']
        ];
        $assets_data_diff_fam[$result['key']] = [
            'score' => $result['score']
        ];
    }

    if(count())

    $in   = str_repeat('?,', count($assets_ids) - 1).'?';
    $sql  =
        "SELECT `Product Family Category Key`,`Product Availability State`,`Product ID`,`Product Code`,`Product Name`,`Product Units Per Case`,`Product Status`,`Product Web State`,`Product Web Configuration`,`Product Availability`,`Product Number of Parts` FROM `Product Dimension` WHERE `Product ID` IN ($in)";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        $assets_ids
    );

    while ($row = $stmt->fetch()) {


        if ($row['Product Family Category Key'] != $_data['args']['family_key']) {
            switch ($row['Product Status']) {
                case 'Discontinuing':
                    $icon_classes = 'fa fa-fw fa-cube warning';
                    break;
                case 'Discontinued':

                    $icon_classes = 'fa fa-fw fa-cube very_discreet';
                    break;
                case 'Suspended':

                    $icon_classes = 'fa fa-fw fa-cube error';
                    break;
                default:

                    $icon_classes = 'fa fa-fw fa-cube';
                    break;
            }
            switch ($row['Product Web Configuration']) {
                case 'Online Force Out of Stock':
                    $icon_classes .= '|fa fa-fw fa-stop red';
                    break;

                case 'Online Force For Sale':
                    $icon_classes .= '|fa fa-fw fa-stop';

                    switch ($row['Product Availability State']) {
                        case 'OnDemand':
                        case 'Normal':
                        case 'Excess':
                            $icon_classes .= ' green';
                            break;
                        case 'VeryLow':
                        case 'Error':
                        case 'OutofStock':
                        case 'Low':
                            $icon_classes .= ' yellow';
                            break;
                        default:
                            break;
                    }

                    break;
                case 'Online Auto':
                    $icon_classes .= '|fa fa-fw fa-circle';

                    switch ($row['Product Availability State']) {
                        case 'OnDemand':
                        case 'Normal':
                        case 'Excess':
                            $icon_classes .= ' green';
                            break;
                        case 'VeryLow':
                        case 'Low':
                            $icon_classes .= ' yellow';
                            break;
                        case 'Error':
                        case 'OutofStock':
                            $icon_classes .= ' red';
                            break;
                        default:
                            break;
                    }

                    break;

            }
            $icons = '';
            foreach (preg_split('/\|/', $icon_classes) as $icon_class) {
                $icons .= "<i class='$icon_class'></i> ";
            }

            $code = $row['Product Code'];
            $name = $row['Product Units Per Case'].'x '.$row['Product Name'];

            $assets_data[$row['Product ID']]['data'] = [
                $icons,
                $code,
                $name
            ];

        }else{
            unset($assets_data[$row['Product ID']]);
        }


    }


    $html        = '';
    $assets_data = array_slice($assets_data, 0, $max_results);
    foreach ($assets_data as $asset_data) {
        $html .= '<tr>
                <td>'.$asset_data['data'][0].'</td><td>'.$asset_data['data'][1].'</td><td>'.$asset_data['data'][2].'</td>
                </tr>';

    }

    echo json_encode(
        ['html' => $html]
    );
}

