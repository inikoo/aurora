<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  17 December 2018 at 15:22:02 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'delivery_notes.ready_to_pick';
$ar_file = 'ar_delivery_notes_tables.php';
$tipo    = 'delivery_notes_ready_to_pick';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number'   => array('label' => _('Number')),
    'customer' => array('label' => _('Customer')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);



$smarty->assign('table_top_lower_template', 'delivery_note_process.edit.tpl');


include('utils/get_table_html.php');


?>
