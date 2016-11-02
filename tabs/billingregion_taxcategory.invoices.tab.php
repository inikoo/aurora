<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:  23 December 2015 at 12:26:00 GMT+8, Macao
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'billingregion_taxcategory.invoices';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'billingregion_taxcategory.invoices';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['billingregion_taxcategory']['to'])) {
    $default['to']
        = $_SESSION['table_state']['billingregion_taxcategory']['to'];
}
if (isset($_SESSION['table_state']['billingregion_taxcategory']['from'])) {
    $default['from']
        = $_SESSION['table_state']['billingregion_taxcategory']['from'];
}
if (isset($_SESSION['table_state']['billingregion_taxcategory']['period'])) {
    $default['period']
        = $_SESSION['table_state']['billingregion_taxcategory']['period'];
}
if (isset($_SESSION['table_state']['billingregion_taxcategory']['excluded_stores'])) {
    $default['excluded_stores']
        = $_SESSION['table_state']['billingregion_taxcategory']['excluded_stores'];
}
$table_views = array();

$table_filters = array(
    'customer' => array(
        'label' => _('Customer'),
        'title' => _('Customer name')
    ),
    'number'   => array(
        'label' => _('Number'),
        'title' => _('Invoice number')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


$smarty->assign('hide_period', true);

include 'utils/get_table_html.php';


?>
