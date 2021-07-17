<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 Jun 2021 00:57  +0800, Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if ($account->get('Account Warehouses') == 0) {

    $html = '<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'), '<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';

    return;
}
if ($user->can_view('fulfilment')) {


    $tab     = 'fulfilment.stored_parts';
    $ar_file = 'ar_fulfilment_tables.php';
    $tipo    = 'stored_parts';

    $default = $user->get_tab_defaults($tab);

    $table_views = array(
        'overview' => array('label' => _('Overview')),


    );

    $table_filters = array(
        'reference' => array(
            'label' => _('Reference'),
            'title' => _('Part reference')
        ),

    );


    $parameters = array(
        'parent'     => $state['parent'],
        'parent_key' => $state['parent_key'],

    );


    $table_buttons = array();


    include 'utils/get_table_html.php';

} else {
    try {
        $html = $smarty->fetch('access_denied');
    } catch (Exception $e) {
    }
}

