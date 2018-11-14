<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 November 2018 at 14:25:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

if (!$user->can_view('locations') or !in_array(
        $state['warehouse']->id, $user->warehouses
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
