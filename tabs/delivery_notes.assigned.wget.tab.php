<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  01-06-2019 16:45:38 BST Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'delivery_notes.assigned';
$ar_file = 'ar_delivery_notes_tables.php';
$tipo    = 'delivery_notes_assigned';

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
