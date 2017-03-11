<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 August 2016 at 13:08:15 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'agent.client_orders';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'agent_client_orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),
);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => ''

);


$table_buttons = array();


$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'js_code', 'js/injections/supplier.orders.'.(_DEVEL ? '' : 'min.').'js'
);

include 'utils/get_table_html.php';

?>
