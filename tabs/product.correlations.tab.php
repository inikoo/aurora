<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   13 January 2020  16:23::23  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3



*/





use Elasticsearch\ClientBuilder;


$size = 20;


if (isset($_SESSION['island_state']['correlations']['period'])) {
    $period = $_SESSION['island_state']['correlations']['period'];

} else {
    $_SESSION['island_state']['correlations']['period'] = 'all';

    $period = 'all';

}

switch ($period) {
    case 'all':
        $period_suffix = '';
        break;

    default:
        $period_suffix = '_'.$period;
        break;
}


//print_r($state);

$asset = $state['_object'];

/**
 * @var $family \ProductCategory
 */
$family = get_object('Category', $asset->get('Product Family Category Key'));

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

$params = [
    'index' => strtolower('au_customers_'.strtolower(DNS_ACCOUNT_CODE)),

    'body' =>

        [

            "query"        => [

                'term' => [
                    'products_bought'.$period_suffix => $asset->id
                ],


            ],
            'aggregations' => [
                'products' => [
                    'significant_terms' => [
                        "field"         => "products_bought".$period_suffix,
                        "min_doc_count" => 1,
                        "size"          => $family->get('Category Number Subjects') + $size + 5
                    ]
                ]
            ]
        ],

    '_source' => [
        'store_key',

    ],
    'size'    => 1


];


$result      = $client->search($params);

$assets_ids  = [];
$assets_data = [];
foreach ($result['aggregations']['products']['buckets'] as $result) {
    if ($result['key'] == $asset->id) {
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


if (count($assets_ids) > 0) {

    $in   = str_repeat('?,', count($assets_ids) - 1).'?';
    $sql  =
        "SELECT  `Product Store Key`,`Product Family Category Key`,`Product Availability State`,`Product ID`,`Product Code`,`Product Name`,`Product Units Per Case`,`Product Status`,`Product Web State`,`Product Web Configuration`,`Product Availability`,`Product Number of Parts` FROM `Product Dimension` WHERE `Product ID` IN ($in)";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        $assets_ids
    );
    while ($row = $stmt->fetch()) {

        if ($row['Product Store Key'] != $asset->get('Store Key')) {
            unset($assets_data[$row['Product ID']]);
            unset($assets_data_diff_fam[$row['Product ID']]);

            continue;
        }

        $icon_classes = '';
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


        $assets_data[$row['Product ID']]['icons'] = $icons;
        $assets_data[$row['Product ID']]['code']  =  sprintf('<span class="link" onclick="\'products/%d/%d\'">%s</span>',$row['Product Store Key'],$row['Product ID'],$row['Product Code']);
        $assets_data[$row['Product ID']]['name']  = $row['Product Units Per Case'].'x '.$row['Product Name'];

        if ($row['Product Family Category Key'] != $asset->get('Product Family Category Key')) {
            $assets_data_diff_fam[$row['Product ID']]['icons'] = $icons;
            $assets_data_diff_fam[$row['Product ID']]['code']  = $row['Product Code'];
            $assets_data_diff_fam[$row['Product ID']]['name']  = $row['Product Units Per Case'].'x '.$row['Product Name'];
        } else {
            unset($assets_data_diff_fam[$row['Product ID']]);
        }


    }
} else {
    $assets_data_diff_fam = [];
    $assets_data          = [];
}


$tables = array(
    [
        'id'     => 'correlated_products',
        'data'   => json_encode(
            [
                'object'    => $asset->get_object_name(),
                'key'       => $asset->id,
                'store_key' => $asset->get('Store Key'),
                'size'      => $size + 1
            ]
        ),
        'title'  => _("Customers also bought"),
        'assets' => array_slice($assets_data, 0, 20)
    ],
    [
        'id'     => 'correlated_products_excl_family',
        'data'   => json_encode(
            [
                'object'     => $asset->get_object_name(),
                'key'        => $asset->id,
                'store_key'  => $asset->get('Store Key'),
                'size'       => $family->get('Category Number Subjects') + $size + 1,
                'family_key' => $family->id
            ]
        ),
        'title'  => _("Customers also bought").' <span class="small">('._('excluding same family').')</span>',
        'assets' => array_slice($assets_data_diff_fam, 0, 20)
    ]
);


$smarty->assign('period', $period);

$smarty->assign('tables', $tables);


$html = $smarty->fetch('asset_correlations.tpl');