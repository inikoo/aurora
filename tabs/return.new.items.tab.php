<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 November 2018 at 14:11:53 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'return.new.items';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'return.new.items';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Description'),
        'title' => _('Description')
    ),
   

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Product code')
    )

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();

$smarty->assign('table_buttons', $table_buttons);


$smarty->assign(
    'js_code', 'js/injections/return.new.'.(_DEVEL ? '' : 'min.').'js'
);


include('utils/get_table_html.php');


?>
