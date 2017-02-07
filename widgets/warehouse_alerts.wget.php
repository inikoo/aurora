<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 February 2017 at 21:48:12 GMT+8, Cyberjaya, Kuala Lumpur
 Copyright (c) 2017, Inikoo

 Version 3.0
*/


function get_warehouse_alerts( $db, $warehouse,$account, $user, $smarty) {




    $html = '';




    $data = get_widget_data_inverse(
        $warehouse->get('Warehouse Part Locations Errors'),
        $warehouse->get('Warehouse Part Locations'),
        $warehouse->get('Warehouse Tolerable Percentage Part Locations Errors'),
        $warehouse->get('Warehouse Max Percentage Part Locations Errors')

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/warehouse.locations_with_errors.dbard.tpl'
        );
    }




    $data = get_widget_data(
        $warehouse->get('Warehouse Paid Ordered Parts To Replenish'),
        $warehouse->get('Warehouse Paid Ordered Parts'),
        $warehouse->get('Warehouse Tolerable Percentage Paid Ordered Parts To Replenish'),
        $warehouse->get('Warehouse Max Percentage Paid Ordered Parts To Replenish')

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/warehouse.parts_to_replenish.dbard.tpl'
        );
    }




    return $html;




}


function get_widget_data_inverse($value, $total, $min, $max) {

    $data = array(
        'ok'        => false,
        'color_min' => '',
        'color_max' => '',
        'value'     => '',
        'total'     => '',
        'min'       => '',
        'max'       => ''
    );

    if ($total == 0) {
        return $data;
    }
    $data['ok']    = true;
    $data['value'] = $value;
    $data['total'] = $total;
    $percentage    = $value / $total;

    if ($percentage < $min) {
        $data['color_min'] = '#84c535';//green
        $data['color_max'] = '#f7da40';//yellow
        $data['min']       = 0;
        $data['max']       = $min;

    } else if($percentage >= $max) {
        $data['color_min'] = '#E0115F';//red
        $data['color_max'] = '#E0115F';//red
        $data['min']       = 0;
        $data['max']       = $min;

    } else {
        $data['color_min'] = '#f7da40';//yellow
        $data['color_max'] = '#E0115F';//red
        $data['min']       = $min;
        $data['max']       = $max;
    }

    return $data;
}



function get_widget_data($value, $total, $min, $max) {

    $data = array(
        'ok'        => false,
        'color_min' => '',
        'color_max' => '',
        'value'     => '',
        'total'     => '',
        'min'       => '',
        'max'       => ''
    );

    if ($total == 0) {
        return $data;
    }
    $data['ok']    = true;
    $data['value'] = $value;
    $data['total'] = $total;
    $percentage    = $value / $total;



    if ($percentage < $min) {
        $data['color_min'] = '#84c535';//green
        $data['color_max'] = '#f7da40';//yellow
        $data['min']       = 0;
        $data['max']       = $min;

    } else if($percentage >= $max) {
        $data['color_min'] = '#E0115F';//red
        $data['color_max'] = '#E0115F';//red
        $data['min']       = 0;
        $data['max']       = $min;

    } else {
        $data['color_min'] = '#f7da40';//yellow
        $data['color_max'] = '#E0115F';//red
        $data['min']       = $min;
        $data['max']       = $max;
    }

    return $data;
}



?>
