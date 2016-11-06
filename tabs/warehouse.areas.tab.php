<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 September 2016 at 12:05:03 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

if (!$user->can_view('locations') or !in_array(
        $state['key'], $user->warehouses
    )
) {
    $html = '';
} else {


    $tab     = 'warehouse.areas';
    $ar_file = 'ar_warehouse_tables.php';
    $tipo    = 'areas';

    $default = $user->get_tab_defaults($tab);


    $table_views = array();

    $table_filters = array(
        'code' => array('label' => _('Code')),

    );

    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );


    include 'utils/get_table_html.php';
}

?>
