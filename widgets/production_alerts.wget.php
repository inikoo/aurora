<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2016 at 18:16:53 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function get_production_alerts($supplier, $db, $account, $user, $smarty) {

    $html = '';


    $data = get_widget_data(
        $supplier->get('Supplier Production Part Locations Errors'), $supplier->get('Supplier Production Part Locations'), $supplier->get(
        'Supplier Production Tolerable Percentage Part Locations Errors'
    ), $supplier->get(
        'Supplier Production Max Percentage Part Locations Errors'
    )

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/production.locations_with_errors.dbard.tpl'
        );
    }

    $data = get_widget_data(
        $supplier->get('Supplier Production Paid Ordered Parts Todo'), $supplier->get('Supplier Number Parts'), $supplier->get(
        'Supplier Production Tolerable Percentage Paid Ordered Parts Todo'
    ), $supplier->get(
        'Supplier Production Max Percentage Paid Ordered Parts Todo'
    )

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.todo.ordered.dbard.tpl');
    }

    $data = get_widget_data(
        $supplier->get('Supplier Number Todo Parts'), $supplier->get('Supplier Number Parts'), $supplier->get('Supplier Tolerable Percentage Todo Parts'),
        $supplier->get('Supplier Max Percentage Todo Parts')

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.todo.dbard.tpl');
    }


    $data = get_widget_data(
        $supplier->get('Supplier Number Surplus Parts'), $supplier->get('Supplier Number Parts'), $supplier->get('Supplier Tolerable Percentage Surplus Parts'),
        $supplier->get('Supplier Max Percentage Surplus Parts')

    );
    if ($data['ok']) {
        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.surplus.dbard.tpl');
    }






    $data = get_widget_data(
        $supplier->get('Supplier Paid Ordered Parts To Replenish'),
        $supplier->get('Supplier Paid Ordered Parts'),
        $supplier->get('Supplier Tolerable Percentage Paid Ordered Parts To Replenish'),
        $supplier->get('Supplier Max Percentage Paid Ordered Parts To Replenish')

    );


    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/supplier.parts_to_replenish.dbard.tpl'
        );
    }





    return $html;

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
        $data['color_min'] = '#84c535';
        $data['color_max'] = '#f7da40';
        $data['min']       = 0;
        $data['max']       = $min;

    } else {
        $data['color_min'] = '#f7da40';
        $data['color_max'] = '#E0115F';
        $data['min']       = $min;
        $data['max']       = $max;
    }

    return $data;
}


?>
