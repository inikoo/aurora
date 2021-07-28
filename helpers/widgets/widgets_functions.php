<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 28 Jul 2021 04:05:15 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

function get_widget_data($value, $total, $min, $max): array {

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

function get_widget_data_inverse($value, $total, $min, $max): array {

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



    if ($percentage > $min) {
        $data['color_min'] = '#84c535';//green
        $data['color_max'] = '#f7da40';//yellow
        $data['min']       = 0;
        $data['max']       = $min;

    } else if($percentage <= $max) {
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
