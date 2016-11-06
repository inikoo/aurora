<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 July 2016 at 13:44:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'hr.history';
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
    'parent'     => 'hr',
    'parent_key' => 1,

);

//$smarty->assign('title', _("Employees's history"));
//$smarty->assign('view_position', _("Employees's history"));


include('utils/get_table_html.php');

?>
