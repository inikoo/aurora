<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17:57:44 MYT Tuesday, 7 July 2020, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

if (!$user->can_view('locations') or !in_array(
        $state['key'], $user->warehouses
    )) {
    $html = '';
} else {


    $warehouse = $state['warehouse'];


    $tab     = 'warehouse.deleted_locations';
    $ar_file = 'ar_warehouse_tables.php';
    $tipo    = 'deleted_locations';

    $default = $user->get_tab_defaults($tab);


    $table_views = array();

    $table_filters = array(
        'code' => array(
            'label' => _('Code'),
            'title' => _('Location code')
        ),

    );

    $parameters = array(
        'parent'     => $state['parent'],
        'parent_key' => $state['parent_key'],

    );


    $table_buttons = array();


    $smarty->assign('table_buttons', $table_buttons);


    $smarty->assign('title', _('Deleted locations'));


    $smarty->assign(
        'view_position', '<span onclick=\"change_view(\'warehouse/'.$warehouse->id.'\')\"><i class=\"fal  fa-warehouse-alt\"></i> <span class=\"id Warehouse_Code\">'.$warehouse->get('Code').'</span></span><i class=\"fa fa-angle-double-right separator\"></i>  '._(
                           'Deleted locations'
                       ).' </span>'
    );


    include 'utils/get_table_html.php';
}


