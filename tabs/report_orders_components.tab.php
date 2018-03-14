<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:12 March 2018 at 13:09:54 GMT+8, Kuala Lumpur, Malaydia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'report_orders_components';
$ar_file = 'ar_reports_tables.php';
$tipo    = 'orders_components';

$default = $user->get_tab_defaults($tab);

if (isset($_SESSION['table_state']['report_orders_components']['to'])) {
    $default['to'] = $_SESSION['table_state']['report_orders_components']['to'];
}
if (isset($_SESSION['table_state']['report_orders_components']['from'])) {
    $default['from'] = $_SESSION['table_state']['report_orders_components']['from'];
}
if (isset($_SESSION['table_state']['report_orders_components']['period'])) {
    $default['period'] = $_SESSION['table_state']['report_orders_components']['period'];
}
if (isset($_SESSION['table_state']['report_orders_components']['excluded_stores'])) {
    $default['excluded_stores']
        = $_SESSION['table_state']['report_orders_components']['excluded_stores'];
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


$html='<div class="smaller">'.$html.'</div>';

?>
