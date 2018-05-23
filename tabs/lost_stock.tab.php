<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 10:45:54 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'lost_stock';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'lost_stock';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['lost_stock']['to'])) {
    $default['to'] = $_SESSION['table_state']['lost_stock']['to'];
}
if (isset($_SESSION['table_state']['lost_stock']['from'])) {
    $default['from'] = $_SESSION['table_state']['lost_stock']['from'];
}
if (isset($_SESSION['table_state']['lost_stock']['period'])) {
    $default['period'] = $_SESSION['table_state']['lost_stock']['period'];
}
$table_views = array();

$table_filters = array(
    'part_reference' => array('label' => _('Part')),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,
);



include 'utils/get_table_html.php';


?>
