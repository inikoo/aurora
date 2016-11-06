<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2016 at 14:54:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'supplier.order.supplier_parts';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'supplier.order.supplier_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Description'),
        'title' => _('Description')
    ),

);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),
    'name'      => array(
        'label' => _('Name'),
        'title' => _('Part name')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons   = array();
$table_buttons[] = array(
    'icon'  => 'bars',
    'title' => _('items'),
    'id'    => 'shortcut_to_items',


);
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
