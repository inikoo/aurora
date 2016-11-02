<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2016 at 19:04:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'ec_sales_list';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'ec_sales_list';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['ec_sales_list']['to'])) {
    $default['to'] = $_SESSION['table_state']['ec_sales_list']['to'];
}
if (isset($_SESSION['table_state']['ec_sales_list']['from'])) {
    $default['from'] = $_SESSION['table_state']['ec_sales_list']['from'];
}
if (isset($_SESSION['table_state']['ec_sales_list']['period'])) {
    $default['period'] = $_SESSION['table_state']['ec_sales_list']['period'];
}
if (isset($_SESSION['table_state']['ec_sales_list']['excluded_stores'])) {
    $default['excluded_stores']
        = $_SESSION['table_state']['ec_sales_list']['excluded_stores'];
}
$table_views = array();

$table_filters = array(
    //	'customer'=>array('label'=>_('Customer'), 'title'=>_('Customer name')),
    'tax_number' => array('label' => _('Tax number')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


//$smarty->assign('hide_period',true);

include 'utils/get_table_html.php';


?>
