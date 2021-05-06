<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6th May 2021 21:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'warehouse_bonus_report';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'warehouse_bonus_report';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['pickers']['to'])) {
    $default['to'] = $_SESSION['table_state']['pickers']['to'];
}
if (isset($_SESSION['table_state']['pickers']['from'])) {
    $default['from'] = $_SESSION['table_state']['pickers']['from'];
}
if (isset($_SESSION['table_state']['pickers']['period'])) {
    $default['period'] = $_SESSION['table_state']['pickers']['period'];
}
$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);


$table_filters = array(
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,
);


//$smarty->assign('hide_period',true);

include 'utils/get_table_html.php';



