<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 January 2017 at 15:47:16 GMT, Sheffield UK

 Copyright (c) 2017, Inikoo

 Version 3.0
*/


function get_inventory_alerts($db, $account, $user, $smarty) {

    $html = '';


    $active = $account->get('Account Active Parts Number') + $account->get('Account Discontinuing Parts Number');

    $active_plus_in_process = $active + $account->get('Account In Process Parts Number');


    $data = get_widget_data(

        $active_plus_in_process - $account->get('Account Active Parts with SKO Barcode Number'), $active_plus_in_process, 0, 0

    );


    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= '<div class="parts_with_no_sko_barcode">'.$smarty->fetch('dashboard/inventory.parts_with_no_sko_barcode.dbard.tpl').'</div>';
    }


    if ($account->get('Account Parts with Barcode Number Error') > 0) {

        $data = get_widget_data(

            $account->get('Account Parts with Barcode Number Error'), $account->get('Account Parts with Barcode Number'), 0, 0

        );


        if ($data['ok']) {


            $smarty->assign('data', $data);
            $html .= '<div class="parts_with_barcode_error">'.$smarty->fetch('dashboard/inventory.parts_with_barcode_errors.dbard.tpl').'</div>';
        }

    }

    $data = get_widget_data(

        $account->get('Account Active Parts with SKO Invalid Weight'), $active_plus_in_process, 0, 0

    );
    if ($data['ok']) {


        $smarty->assign('data', $data);
        $html .= '<div class="parts_with_weight_error">'.$smarty->fetch('dashboard/inventory.parts_with_weight_errors.dbard.tpl').'</div>';
    }


    $data = get_widget_data(
        $account->get('Account Parts No Products'), $active, 0, 0
    );
    if ($data['ok']) {
        $smarty->assign('data', $data);
        $html .= '<div class="parts_no_products_error">'.$smarty->fetch('dashboard/inventory.parts_with_no_products.dbard.tpl').'</div>';
    }

    $data = get_widget_data(
        $account->get('Account Parts Forced not for Sale'), $active, 0, 0
    );
    if ($data['ok']) {
        $smarty->assign('data', $data);
        $html .= '<div class="orced_not_for_sale_on_website_error">'.$smarty->fetch('dashboard/inventory.parts_forced_not_for_sale_on_website.dbard.tpl').'</div>';
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

    } else {
        if ($percentage >= $max) {
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
    }

    return $data;
}


