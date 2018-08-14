<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 August 2018 at 12:23:25 GMT+8, Kuta, Bali , Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'sales_representatives';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'sales_representatives';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['sales_representatives']['to'])) {
    $default['to'] = $_SESSION['table_state']['sales_representatives']['to'];
}
if (isset($_SESSION['table_state']['sales_representatives']['from'])) {
    $default['from'] = $_SESSION['table_state']['sales_representatives']['from'];
}
if (isset($_SESSION['table_state']['sales_representatives']['period'])) {
    $default['period'] = $_SESSION['table_state']['sales_representatives']['period'];
}
$table_views = array();

$table_filters = array(
    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,
);



include 'utils/get_table_html.php';


?>
