<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29-07-2019 15:15:00 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$tab     = 'customer_list.mailshots';
$ar_file = 'ar_mailshots_tables.php';
$tipo    = 'mailshots';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('name')
    )

);


$table_buttons = array();


$table_buttons[] = array(
    'icon'  => 'plus',
    'title' => _('New marketing mailshot'),
    'id'    => 'new_mailshot',
    'attr'  => array(
        'parent'     => 'Store',
        'parent_key' => $state['_object']->get('Store Key'),

    )

);

$smarty->assign(
    'js_code', 'js/injections/new_marketing_mailshot.'.(_DEVEL ? '' : 'min.').'js'
);


$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



