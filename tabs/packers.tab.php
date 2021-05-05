<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 January 2018 at 17:28:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'packers';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'packers';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['packers']['to'])) {
    $default['to'] = $_SESSION['table_state']['packers']['to'];
}
if (isset($_SESSION['table_state']['packers']['from'])) {
    $default['from'] = $_SESSION['table_state']['packers']['from'];
}
if (isset($_SESSION['table_state']['packers']['period'])) {
    $default['period'] = $_SESSION['table_state']['packers']['period'];
}
$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'bonus'  => array(
        'label' => _('Bonus'),
        'title' => _('Bonus')
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



