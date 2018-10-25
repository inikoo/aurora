<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 December 2017 at 11:16:12 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'sales';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'sales';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['sales']['to'])) {
    $default['to'] = $_SESSION['table_state']['sales']['to'];
}
if (isset($_SESSION['table_state']['sales']['from'])) {
    $default['from'] = $_SESSION['table_state']['sales']['from'];
}
if (isset($_SESSION['table_state']['sales']['period'])) {
    $default['period'] = $_SESSION['table_state']['sales']['period'];
}
if (isset($_SESSION['table_state']['sales']['excluded_stores'])) {
    $default['excluded_stores'] = $_SESSION['table_state']['sales']['excluded_stores'];
}


if (isset($state['metadata']['parameters']['currency'])) {
    $_SESSION['table_state']['sales']['currency']=$state['metadata']['parameters']['currency'];
}


if (isset($_SESSION['table_state']['sales']['currency'])) {
    $default['currency'] = $_SESSION['table_state']['sales']['currency'];
}


//print_r($state);



$table_views = array();

$table_filters = array(
    //	'customer'=>array('label'=>_('Customer'), 'title'=>_('Customer name')),
    'store' => array('label' => _('Store')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);

$smarty->assign('table_state',$default);

$smarty->assign('table_top_template', 'control.sales.tpl');
$smarty->assign('table_class', 'with_totals');

include 'utils/get_table_html.php';


?>
