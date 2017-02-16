<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  16 September 2015 14:43:02 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'orders.pending';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'pending_orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'customer' => array('label' => _('Customer')),
    'number'   => array('label' => _('Number')),

);

$parameters = array(
    'parent'     => 'store',
    'parent_key' => $state['parent_key'],
);



$smarty->assign('table_top_lower_template', 'orders_process.edit.tpl');


include('utils/get_table_html.php');


?>
