<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 August 2017 at 12:56:23 GMT+5:30, Delhi Airport, India
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'orders.in_warehouse_no_alerts';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders_in_warehouse_no_alerts';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number'   => array('label' => _('Number')),
    'customer' => array('label' => _('Customer')),
);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);
$smarty->assign('table_top_lower_template_parameters',$parameters);

$smarty->assign('parent',get_object($state['parent'],$state['parent_key']));


//todo select correct warehouse
$warehouse = get_object('warehouse', 1);
$shippers = $warehouse->get_shippers('data', 'Active');

$smarty->assign('shippers', $shippers);
$smarty->assign('number_shippers', count($shippers));

$smarty->assign('table_top_lower_template', 'orders_in_warehouse.edit.tpl');

include('utils/get_table_html.php');