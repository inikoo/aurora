<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2016 at 10:37:32 GMT+8, Yuwu, China
 Copyright (c) 20156 Inikoo

 Version 3

*/


require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'elastic/assets_correlation.elastic.php';

if (!isset($_REQUEST['args']['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}



$tipo = $_REQUEST['args']['tipo'];

switch ($tipo) {


    case 'correlated_products':
    case 'correlated_products_excl_family':

        $data = prepare_values(
            $_REQUEST['args'], array(
                                 'tipo'   => array('type' => 'string'),
                                 'args'   => array('type' => 'string'),
                                 'period' => array('type' => 'string'),
                             )
        );
        correlated_products($data, $db);

        break;


    case 'correlated_categories':
    case 'correlated_categories_excl_dept':
        $data = prepare_values(
            $_REQUEST['args'], array(
                                 'tipo' => array('type' => 'string'),

                                 'args'   => array('type' => 'string'),
                                 'period' => array('type' => 'string'),
                             )
        );
        correlated_categories($data, $db);

        break;


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


function correlated_categories($_data, $db) {


    //    $_SESSION['island_state']['correlations']['period'] = 'all';


    if (isset($_data['period']) and in_array(
            $_data['period'], [
                                'all',
                                '1y',
                                '1q',
                                '1m',
                                '1w'
                            ]
        )) {
        $period = $_data['period'];
    } else {
        $period = 'all';
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


    $result = get_elastic_sales_correlated_assets($_data['args']['key'], $_data['args']['field_name'],$period_suffix,  $_data['args']['size']);



    $assets_ids  = [];
    $assets_data = [];
    foreach ($result['aggregations']['assets']['buckets'] as $result) {
        if ($result['key'] == $_data['args']['key']) {
            continue;
        }
        $assets_ids[]                = $result['key'];
        $assets_data[$result['key']] = [
            'score' => $result['score']
        ];

    }


    if (count($assets_ids) > 0) {

        $in = str_repeat('?,', count($assets_ids) - 1).'?';

        if ($_data['args']['cat_subject'] == 'Product') {
            $sql = "SELECT D.`Category Code` as dept,C.`Category Store Key`,C.`Category Key`,`Product Category Status`,C.`Category Subject`,C.`Category Root Key`,`Product Category Department Category Key`,C.`Category Code`,C.`Category Label` 
                    FROM `Category Dimension` C 
                                left join `Product Category Dimension` B on (C.`Category Key`=B.`Product Category Key`)  
                                 left join `Category Dimension` D on (D.`Category Key`=`Product Category Department Category Key`)  
                                WHERE C.`Category Key` IN ($in)";

        } else {
            $sql = "SELECT `Category Store Key`,C.`Category Key`,`Product Category Status`,`Category Subject`,`Category Root Key`,`Product Category Department Category Key`,`Category Code`,`Category Label` 
                    FROM `Category Dimension` C 
                                left join `Product Category Dimension` B on (C.`Category Key`=B.`Product Category Key`)  
                                WHERE C.`Category Key` IN ($in)";

        }


        $stmt = $db->prepare($sql);
        $stmt->execute(
            $assets_ids
        );


        while ($row = $stmt->fetch()) {


            //print $row['Product Category Department Category Key'].' '.$_data['args']['department_key']."\n";
            if ($_data['tipo'] == 'correlated_categories' or $row['Product Category Department Category Key'] != $_data['args']['department_key']) {


                if ($row['Category Store Key'] != $_data['args']['store_key']) {
                    unset($assets_data[$row['Category Key']]);

                    continue;
                }


                //print "**\n";

                if ($row['Category Root Key'] == $_data['args']['store_fam_category_key'] or $row['Category Root Key'] == $_data['args']['store_dept_category_key']) {
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

                $code = sprintf('<span class="link" onclick="change_view(\'products/%d/category/%d\')">%s</span>', $row['Category Store Key'], $row['Category Key'], $row['Category Code']);
                if ($_data['args']['cat_subject'] == 'Product') {
                    $code .= ' <span class="small very_discreet">('.$row['dept'].')</span>';
                }
                $name = $row['Category Label'];


                $assets_data[$row['Category Key']]['data'] = [
                    $icons,
                    $code,
                    $name
                ];

            } else {
                unset($assets_data[$row['Category Key']]);
            }
        }

    } else {
        $assets_data = [];
    }


    $html        = '';
    $assets_data = array_slice($assets_data, 0, $max_results);
    foreach ($assets_data as $asset_data) {
        $html .= '<tr>
                <td class="icons">'.$asset_data['data'][0].'</td><td class="code">'.$asset_data['data'][1].'</td><td class="truncate">'.$asset_data['data'][2].'</td>
                </tr>';

    }


    echo json_encode(
        ['html' => $html]
    );

}

function correlated_products($_data, $db) {

    if (isset($_data['period']) and in_array(
            $_data['period'], [
                                'all',
                                '1y',
                                '1q',
                                '1m',
                                '1w'
                            ]
        )) {
        $period = $_data['period'];
    } else {
        $period = 'all';
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

    $result = get_elastic_sales_correlated_assets($_data['args']['key'],'products_bought',$period_suffix,  $_data['args']['size']);

    $assets_ids  = [];
    $assets_data = [];
    foreach ($result['aggregations']['assets']['buckets'] as $result) {
        if ($result['key'] == $_data['args']['key']) {
            continue;
        }
        $assets_ids[]                = $result['key'];
        $assets_data[$result['key']] = [
            'score' => $result['score']
        ];

    }

    if (count($assets_ids) > 0) {
        $in   = str_repeat('?,', count($assets_ids) - 1).'?';
        $sql  =
            "SELECT `Product Store Key`,`Product Family Category Key`,`Product Availability State`,`Product ID`,`Product Code`,`Product Name`,`Product Units Per Case`,`Product Status`,`Product Web State`,`Product Web Configuration`,`Product Availability`,`Product Number of Parts` FROM `Product Dimension` WHERE `Product ID` IN ($in)";
        $stmt = $db->prepare($sql);
        $stmt->execute(
            $assets_ids
        );



        while ($row = $stmt->fetch()) {

            if ($row['Product Store Key'] != $_data['args']['store_key']) {
                unset($assets_data[$row['Product ID']]);

                continue;
            }

            if ($_data['tipo'] == 'correlated_products' or $row['Product Family Category Key'] != $_data['args']['family_key']) {

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

                $code = sprintf('<span class="link" onclick="\'products/%d/%d\'">%s</span>',$row['Product Store Key'],$row['Product ID'],$row['Product Code']);
                $name = $row['Product Units Per Case'].'x '.$row['Product Name'];




                $assets_data[$row['Product ID']]['data'] = [
                    $icons,
                    $code,
                    $name
                ];

            } else {
                unset($assets_data[$row['Product ID']]);
            }
        }

    } else {
        $assets_data = [];
    }



    $html        = '';
    $assets_data = array_slice($assets_data, 0, $max_results);
    foreach ($assets_data as $asset_data) {
        $html .= '<tr>
                <td class="icons">'.$asset_data['data'][0].'</td><td class="code">'.$asset_data['data'][1].'</td><td class="truncate">'.$asset_data['data'][2].'</td>
                </tr>';

    }


    echo json_encode(
        ['html' => $html]
    );

}
