<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 August 2017 at 12:56:23 GMT+5:30, Delhi Airport, India
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'orders.in_warehouse_with_alerts';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders_in_warehouse_with_alerts';

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
