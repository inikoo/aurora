<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   13 January 2020  16:23::23  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3



*/

use Elasticsearch\ClientBuilder;


//print_r($state);

$asset = $state['_object'];

/**
 * @var $family \ProductCategory
 */
$family=get_object('Category',$asset->get('Product Family Category Key'));

$client = ClientBuilder::create()->setHosts(get_ES_hosts())->build();

$params = [
    'index' => strtolower('au_customers_'.strtolower(DNS_ACCOUNT_CODE)),

    'body' =>

        [

            "query"        => [
                'match' => [
                    'products_bought' => $asset->id
                ]
            ],
            'aggregations' => [
                'products' => [
                    'significant_terms' => [
                        "field"         => "products_bought",
                        "min_doc_count" => 1,
                        "size"          => $family->get('Category Number Subjects')+25
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
    if($result['key']==$asset->id){
        continue;
    }
    $assets_ids[]                = $result['key'];
    $assets_data[$result['key']] = [
        'score' => $result['score']
    ];
    $assets_data_diff_fam[$result['key']] = [
        'score' => $result['score']
    ];
}

$in   = str_repeat('?,', count($assets_ids) - 1).'?';
$sql  = "SELECT `Product Family Category Key`,`Product Availability State`,`Product ID`,`Product Code`,`Product Name`,`Product Units Per Case`,`Product Status`,`Product Web State`,`Product Web Configuration`,`Product Availability`,`Product Number of Parts` FROM `Product Dimension` WHERE `Product ID` IN ($in)";
$stmt = $db->prepare($sql);
$stmt->execute(
    $assets_ids
);
while ($row = $stmt->fetch()) {


    $icon_classes='';
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
    $icons='';
    foreach(preg_split('/\|/',$icon_classes) as $icon_class){
        $icons.="<i class='$icon_class'></i> ";
    }


    $assets_data[$row['Product ID']]['icons'] = $icons;
    $assets_data[$row['Product ID']]['code'] = $row['Product Code'];
    $assets_data[$row['Product ID']]['name'] = $row['Product Units Per Case'].'x '.$row['Product Name'];

    if($row['Product Family Category Key']!=$asset->get('Product Family Category Key')){
        $assets_data_diff_fam[$row['Product ID']]['icons'] = $icons;
        $assets_data_diff_fam[$row['Product ID']]['code'] = $row['Product Code'];
        $assets_data_diff_fam[$row['Product ID']]['name'] = $row['Product Units Per Case'].'x '.$row['Product Name'];
    }else{
        unset($assets_data_diff_fam[$row['Product ID']]);
    }


}


$tables = array(
    [
        'id'     => 'correlated_products',
        'title'  => _('Products'),
        'assets' => array_slice($assets_data, 0, 20)
    ],
    [
        'id'     => 'correlated_products',
        'title'  => _('Products excluding same family'),
        'assets' => array_slice($assets_data_diff_fam, 0, 20)
    ]
);

$smarty->assign('tables', $tables);
$html = $smarty->fetch('asset_correlations.tpl');