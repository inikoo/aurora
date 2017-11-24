<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2017 at 18:25:08 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'replacement.new.items';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'replacement.new.items';

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
    'js_code', 'js/injections/replacement.new.'.(_DEVEL ? '' : 'min.').'js'
);


include('utils/get_table_html.php');


?>
