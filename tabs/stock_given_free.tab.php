<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 May 2018 at 10:49:32 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'stock_given_free';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'stock_given_free';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['stock_given_free']['to'])) {
    $default['to'] = $_SESSION['table_state']['stock_given_free']['to'];
}
if (isset($_SESSION['table_state']['stock_given_free']['from'])) {
    $default['from'] = $_SESSION['table_state']['stock_given_free']['from'];
}
if (isset($_SESSION['table_state']['stock_given_free']['period'])) {
    $default['period'] = $_SESSION['table_state']['stock_given_free']['period'];
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
