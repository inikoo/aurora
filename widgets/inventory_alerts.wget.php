<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 January 2017 at 15:47:16 GMT, Sheffield UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


function get_inventory_alerts( $db, $account, $user, $smarty) {

    $html = '';

    $data = get_widget_data(

        $account->get('Account Active Parts Number')-$account->get('Account Active Parts with SKO Barcode Number'),
        $account->get('Account Active Parts Number'),
        0,
        0

    );
    

    
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch(
            'dashboard/inventory.parts_with_no_sko_barcode.dbard.tpl'
        );
    }

    return $html;


    $data = get_widget_data(
        $account->get('Supplier Production Paid Ordered Parts Todo'), $account->get('Supplier Number Parts'), $account->get(
        'Supplier Production Tolerable Percentage Paid Ordered Parts Todo'
    ), $account->get(
        'Supplier Production Max Percentage Paid Ordered Parts Todo'
    )

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.todo.ordered.dbard.tpl');
    }

    $data = get_widget_data(
        $account->get('Supplier Number Todo Parts'), $account->get('Supplier Number Parts'), $account->get('Supplier Tolerable Percentage Todo Parts'),
        $account->get('Supplier Max Percentage Todo Parts')

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.todo.dbard.tpl');
    }


    $data = get_widget_data(
        $account->get('Supplier Number Surplus Parts'), $account->get('Supplier Number Parts'), $account->get('Supplier Tolerable Percentage Surplus Parts'),
        $account->get('Supplier Max Percentage Surplus Parts')

    );
    if ($data['ok']) {
        $smarty->assign('data', $data);
        $html .= $smarty->fetch('dashboard/production.surplus.dbard.tpl');
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
