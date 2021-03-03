<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2016 at 18:16:53 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


function get_production_alerts($production, $db, $account, $user, $smarty) {

    $html = '';


    $data = get_widget_data(
        $production->get('Supplier Production Part Locations Errors'), $production->get('Supplier Production Part Locations'), $production->get(
        'Supplier Production Tolerable Percentage Part Locations Errors'
    ), $production->get(
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
        $production->get('Supplier Production Paid Ordered Parts Todo'), $production->get('Supplier Number Parts'),
        $production->get(
        'Supplier Production Tolerable Percentage Paid Ordered Parts Todo'
    ), $production->get(
        'Supplier Production Max Percentage Paid Ordered Parts Todo'
    )

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.todo.ordered.dbard.tpl');
    }

    $data = get_widget_data(
        $production->get('Production to do parts'), $production->get('Supplier Number Parts'), $production->get('Supplier Tolerable Percentage Todo Parts'),
        $production->get('Supplier Max Percentage Todo Parts')

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.todo.dbard.tpl');
    }


    $data = get_widget_data(
        $production->get('Supplier Number Surplus Parts'), $production->get('Supplier Number Parts'), $production->get('Supplier Tolerable Percentage Surplus Parts'),
        $production->get('Supplier Max Percentage Surplus Parts')

    );
    if ($data['ok']) {
        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.surplus.dbard.tpl');
    }






    $data = get_widget_data(
        $production->get('Supplier Paid Ordered Parts To Replenish'),
        $production->get('Supplier Paid Ordered Parts'),
        $production->get('Supplier Tolerable Percentage Paid Ordered Parts To Replenish'),
        $production->get('Supplier Max Percentage Paid Ordered Parts To Replenish')

    );



    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/supplier.parts_to_replenish.dbard.tpl'
        );
    }




    $data = get_widget_data(
        $production->get('Supplier Part Locations To Replenish'),
        $production->get('Supplier Replenishable Part Locations'),
        $production->get('Supplier Tolerable Percentage Part Locations To Replenish'),
        $production->get('Supplier Max Percentage Part Locations To Replenish')

    );

    if ($data['ok']) {



        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/supplier.location_parts_to_replenish.dbard.tpl');
    }


    return $html;

}


function get_widget_data($value, $total, $min, $max) {

    if(!$value){
        $value=0;
    }


    $data = array(
        'ok'        => false,
        'color_min' => '',
        'color_max' => '',
        'value'     => '',
        'total'     => '',
        'min'       => '',
        'max'       => ''
    );
    $data['ok']    = true;



    if ($total == 0) {

        $data['total'] = 1;
        $data['value'] = 0;
        $data['color_min'] = '#84c535';
        $data['color_max'] = '#f7da40';
        $data['min']       = 0;
        $data['max']       = 1;

        return $data;

    }else{
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



}



