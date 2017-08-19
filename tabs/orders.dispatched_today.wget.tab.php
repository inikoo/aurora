<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 August 2017 at 15:05:26 GMT+5:30, Flight Delhi India to Bangkok
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'orders.dispatched_today';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders_dispatched_today';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'customer' => array('label' => _('Customer')),
    'number'   => array('label' => _('Number')),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);



$smarty->assign('table_top_lower_template', 'orders_process.edit.tpl');


include('utils/get_table_html.php');


?>
