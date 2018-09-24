<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 August 2018 at 16:00:03 GMT+8, Kuta, Bali , Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'prospect_agents';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'prospect_agents';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['prospect_agents']['to'])) {
    $default['to'] = $_SESSION['table_state']['prospect_agents']['to'];
}
if (isset($_SESSION['table_state']['prospect_agents']['from'])) {
    $default['from'] = $_SESSION['table_state']['prospect_agents']['from'];
}
if (isset($_SESSION['table_state']['prospect_agents']['period'])) {
    $default['period'] = $_SESSION['table_state']['prospect_agents']['period'];
}
$table_views = array();


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'percentages'  => array(
        'label' => _('Percentages'),
        'title' => _('Percentages')
    ),


);


$table_filters = array(
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,
);



include 'utils/get_table_html.php';


?>
