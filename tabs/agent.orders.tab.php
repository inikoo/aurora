<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 12:18:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'agent.orders';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'  => 'plus',
    'title' => _('New purchase order'),
    'id'    => 'new_purchase_order',
    'attr'  => array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],
    )


);

$smarty->assign('table_buttons', $table_buttons);

$smarty->assign(
    'js_code', 'js/injections/supplier.orders.'.(_DEVEL ? '' : 'min.').'js'
);

include 'utils/get_table_html.php';

?>
