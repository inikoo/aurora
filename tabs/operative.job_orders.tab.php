<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5:08 pm Friday, 26 June 2020 (MYT) Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

$tab     = 'operative.job_orders';
$ar_file = 'ar_production_tables.php';
$tipo    = 'production_orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),
);

$parameters = array(
    'parent'     => 'operative',
    'parent_key' => $state['key'],

);

/*
$table_buttons[] = array(
    'icon'  => 'plus',
    'title' => _('New job order'),
    'id'    => 'new_purchase_order',
    'attr'  => array(
        'parent'     => $state['object'],
        'parent_key' => $state['key'],
    )
);

$smarty->assign('table_buttons', $table_buttons);
$smarty->assign('js_code', 'js/injections/supplier.orders.'.(_DEVEL ? '' : 'min.').'js');

*/


include 'utils/get_table_html.php';


