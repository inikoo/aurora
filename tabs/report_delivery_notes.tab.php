<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:9 March 2018 at 16:29:59 GMT+8, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'report_delivery_notes';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'delivery_notes';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['report_delivery_notes']['to'])) {
    $default['to'] = $_SESSION['table_state']['report_delivery_notes']['to'];
}
if (isset($_SESSION['table_state']['report_delivery_notes']['from'])) {
    $default['from'] = $_SESSION['table_state']['report_delivery_notes']['from'];
}
if (isset($_SESSION['table_state']['report_delivery_notes']['period'])) {
    $default['period'] = $_SESSION['table_state']['report_delivery_notes']['period'];
}
if (isset($_SESSION['table_state']['report_delivery_notes']['excluded_stores'])) {
    $default['excluded_stores']
        = $_SESSION['table_state']['report_delivery_notes']['excluded_stores'];
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

include 'utils/get_table_html.php';


?>
