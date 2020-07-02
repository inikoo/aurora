<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 October 2018 at 19:46:50 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'sales_invoice_category';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'sales_invoice_category';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['sales_invoice_category']['to'])) {
    $default['to'] = $_SESSION['table_state']['sales_invoice_category']['to'];
}
if (isset($_SESSION['table_state']['sales_invoice_category']['from'])) {
    $default['from'] = $_SESSION['table_state']['sales_invoice_category']['from'];
}
if (isset($_SESSION['table_state']['sales_invoice_category']['period'])) {
    $default['period'] = $_SESSION['table_state']['sales_invoice_category']['period'];
}
if (isset($_SESSION['table_state']['sales_invoice_category']['excluded_stores'])) {
    $default['excluded_stores'] = $_SESSION['table_state']['sales_invoice_category']['excluded_stores'];
}


if (isset($state['metadata']['parameters']['currency'])) {
    $_SESSION['table_state']['sales_invoice_category']['currency']=$state['metadata']['parameters']['currency'];
}


if (isset($_SESSION['table_state']['sales_invoice_category']['currency'])) {
    $default['currency'] = $_SESSION['table_state']['sales_invoice_category']['currency'];
}


$table_views = array();

$table_filters = array(
    'category' => array('label' => _('Category')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);

$smarty->assign('table_state',$default);

$smarty->assign('table_top_template', 'control.sales.tpl');
$smarty->assign('table_class', 'with_totals');

include 'utils/get_table_html.php';
