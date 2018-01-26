<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2018 at 14:27:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'part.locations';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'part_locations';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Location code')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons = array();

$smarty->assign('table_buttons', $table_buttons);


$smarty->assign('table_top_template', 'part_locations_notes.edit.tpl');


include 'utils/get_table_html.php';


?>
