<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 March 2018 at 16:28:46 GMT+8, Kuala Lumpur, Malaydia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'report_orders';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'orders';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['report_orders']['to'])) {
    $default['to'] = $_SESSION['table_state']['report_orders']['to'];
}
if (isset($_SESSION['table_state']['report_orders']['from'])) {
    $default['from'] = $_SESSION['table_state']['report_orders']['from'];
}
if (isset($_SESSION['table_state']['report_orders']['period'])) {
    $default['period'] = $_SESSION['table_state']['report_orders']['period'];
}
if (isset($_SESSION['table_state']['report_orders']['excluded_stores'])) {
    $default['excluded_stores']
        = $_SESSION['table_state']['report_orders']['excluded_stores'];
}
$table_views = array();

$table_filters = array(
    //	'customer'=>array('label'=>_('Customer'), 'title'=>_('Customer name')),
    'store' => array('label' => _('Store')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


//$smarty->assign('hide_period',true);
$smarty->assign('table_class','with_totals');

include 'utils/get_table_html.php';


?>
