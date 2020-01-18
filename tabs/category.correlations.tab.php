<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  15 January 2020  20:14::31  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3



*/

include_once 'elastic/assets_correlation.elastic.php';


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


/**
 * @var $asset \ProductCategory
 */
$asset = $state['_object'];

if ($asset->get('Category Subject') == 'Product') {
    $department        = get_object('Category', $asset->get('Product Category Department Category Key'));
    $field_name        = 'families_bought';
    $aggregations_size = (is_numeric($department->get('Category Number Subjects')) ? $department->get('Category Number Subjects') : 0) + $size + 1;

} else {
    $field_name        = 'departments_bought';
    $aggregations_size = $size + 1;
}

$result = get_elastic_sales_correlated_assets($asset->id, $field_name, $period_suffix, $aggregations_size);




$assets_ids  = [];
$assets_data = [];
foreach ($result['aggregations']['assets']['buckets'] as $result) {
    if ($result['key'] == $asset->id) {
        continue;
    }
    $assets_ids[]                          = $result['key'];
    $assets_data[$result['key']]           = [
        'score' => $result['score']
    ];
    $assets_data_diff_dept[$result['key']] = [
        'score' => $result['score']
    ];
}

$store = get_object('store', $asset->get('Store Key'));

if (count($assets_ids) > 0) {

    $in = str_repeat('?,', count($assets_ids) - 1).'?';
    if ($asset->get('Category Subject') == 'Product') {
        $sql = "SELECT D.`Category Code` as dept,C.`Category Store Key`,C.`Category Key`,`Product Category Status`,C.`Category Subject`,C.`Category Root Key`,`Product Category Department Category Key`,C.`Category Code`,C.`Category Label` 
                    FROM `Category Dimension` C 
                                left join `Product Category Dimension` B on (C.`Category Key`=B.`Product Category Key`)  
                                 left join `Category Dimension` D on (D.`Category Key`=`Product Category Department Category Key`)  
                                WHERE C.`Category Key` IN ($in)";

    } else {
        $sql =
            "SELECT `Category Store Key`,C.`Category Key`,`Product Category Status`,`Category Subject`,`Category Root Key`,`Product Category Department Category Key`,`Category Code`,`Category Label` FROM `Category Dimension` C left join `Product Category Dimension` B on (C.`Category Key`=B.`Product Category Key`)  WHERE C.`Category Key` IN ($in)";

    }

    $stmt = $db->prepare($sql);
    $stmt->execute(
        $assets_ids
    );
    while ($row = $stmt->fetch()) {

        if ($row['Category Store Key'] != $asset->get('Store Key')) {
            unset($assets_data[$row['Category Key']]);
            unset($assets_data_diff_dept[$row['Category Key']]);

            continue;
        }

        $icon_classes = '';

        if ($row['Category Root Key'] == $store->get('Store Family Category Key') or $row['Category Root Key'] == $store->get('Store Department Category Key')) {
            $icon_classes = 'fa yellow_main ';
        } else {
            $icon_classes = 'far discreet ';
        }

        if ($row['Category Subject'] == 'Product') {
            $icon_classes .= 'fa-fw fa-folder-open';
        } else {
            $icon_classes .= 'fa-fw fa-folder-tree';
        }

        switch ($row['Product Category Status']) {
            case 'In Process':
                break;

            case 'Active':
                break;
            case 'Suspended':
                $icon_classes .= ' very_discreet red';

                break;
            case 'Discontinued':
                $icon_classes .= ' very_discreet warning';
                break;
            case 'Discontinuing':
                $icon_classes .= ' warning';
                break;

        }


        $icons = '';
        foreach (preg_split('/\|/', $icon_classes) as $icon_class) {
            $icons .= "<i class='$icon_class'></i> ";
        }


        $assets_data[$row['Category Key']]['icons'] = $icons;
        $assets_data[$row['Category Key']]['code']  = sprintf('<span class="link" onclick="change_view(\'products/%d/category/%d\')">%s</span>', $row['Category Store Key'], $row['Category Key'], $row['Category Code']);
        if ($asset->get('Category Subject') == 'Product') {
            $assets_data[$row['Category Key']]['code'] .= ' <span class="small very_discreet">('.$row['dept'].')</span>';
        }

        $assets_data[$row['Category Key']]['name'] = $row['Category Label'];

        if ($row['Product Category Department Category Key'] != $asset->get('Product Category Department Category Key')) {


            $assets_data_diff_dept[$row['Category Key']]['icons'] = $icons;
            $assets_data_diff_dept[$row['Category Key']]['code']  = sprintf('<span class="link" onclick="change_view(\'products/%d/category/%d\')">%s</span>', $row['Category Store Key'], $row['Category Key'], $row['Category Code']);
            if ($asset->get('Category Subject') == 'Product') {
                $assets_data_diff_dept[$row['Category Key']]['code'] .= ' <span class="small very_discreet">('.$row['dept'].')</span>';
            }
            $assets_data_diff_dept[$row['Category Key']]['name'] = $row['Category Label'];
        } else {
            unset($assets_data_diff_dept[$row['Category Key']]);
        }


    }
} else {
    $assets_data_diff_dept = [];
    $assets_data           = [];
}

$tables = [];

$tables[] = [
    'id'     => 'correlated_categories',
    'data'   => json_encode(
        [
            'object'                  => $asset->get_object_name(),
            'key'                     => $asset->id,
            'store_key'               => $asset->get('Store Key'),
            'size'                    => $aggregations_size,
            'field_name'              => $field_name,
            'store_fam_category_key'  => $store->get('Store Family Category Key'),
            'store_dept_category_key' => $store->get('Store Department Category Key'),
            'cat_subject'             => $asset->get('Category Subject')
        ]
    ),
    'title'  => _("Customers also bought"),
    'assets' => array_slice($assets_data, 0, 20)
];

if ($asset->get('Category Subject') == 'Product') {
    $tables[] = [
        'id'     => 'correlated_categories_excl_dept',
        'data'   => json_encode(
            [
                'object'                  => $asset->get_object_name(),
                'key'                     => $asset->id,
                'store_key'               => $asset->get('Store Key'),
                'size'                    => $aggregations_size,
                'department_key'          => $department->id,
                'field_name'              => $field_name,
                'store_fam_category_key'  => $store->get('Store Family Category Key'),
                'store_dept_category_key' => $store->get('Store Department Category Key'),
                'cat_subject'             => $asset->get('Category Subject')
            ]
        ),
        'title'  => _("Customers also bought").' <span class="small">('._('excluding same department').')</span>',
        'assets' => array_slice($assets_data_diff_dept, 0, 20)
    ];
}


$smarty->assign('period', $period);

$smarty->assign('tables', $tables);


$html = $smarty->fetch('asset_correlations.tpl');