<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10-06-2019 21:36:57 MYT   Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'inventory.parts_weight_errors.wget';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'parts_weight_errors';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Reference')),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

$smarty->assign(
    'js_code', 'js/injections/parts_weight_errors.'.(_DEVEL ? '' : 'min.').'js'
);

include 'utils/get_table_html.php';



