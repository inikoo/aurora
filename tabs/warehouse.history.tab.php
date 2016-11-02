<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 October 2015 at 12:56:37 CEST, Malaga Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

if (!$user->can_view('locations') or !in_array(
        $state['key'], $user->warehouses
    )
) {

    $html = '';
} else {

    $tab     = 'warehouse.history';
    $ar_file = 'ar_history_tables.php';
    $tipo    = 'object_history';

    $default = $user->get_tab_defaults($tab);

    $table_views = array();

    $table_filters = array(
        'note' => array(
            'label' => _('Notes'),
            'title' => _('Notes')
        ),
    );

    $parameters = array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],

    );

    include 'utils/get_table_html.php';
}
?>
