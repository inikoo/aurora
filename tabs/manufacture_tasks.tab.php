<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 117 December 2015 at 07:57:13 CET Barcelona Airport, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'manufacture_tasks';
$ar_file = 'ar_production_tables.php';
$tipo    = 'manufacture_tasks';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('Task name')
    ),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('Add task'),
    'reference' => "manufacture_task/new"
);
$smarty->assign('table_buttons', $table_buttons);

$smarty->assign('tipo', $tipo);


$smarty->assign('title', _('Tasks'));

include('utils/get_table_html.php');


?>
