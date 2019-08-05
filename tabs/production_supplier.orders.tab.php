<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15-07-2019 16:39:14 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/



$tab     = 'production_supplier.orders';
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
    'parent'     => 'production_supplier',
    'parent_key' => $state['key'],

);


if ($state['_object']->get('Supplier Type') != 'Archived') {


        $table_buttons[] = array(
            'icon'  => 'plus',
            'title' => _('New jon order'),
            'id'    => 'new_purchase_order',
            'attr'  => array(
                'parent'     => $state['object'],
                'parent_key' => $state['key'],
            )


        );





    $smarty->assign('table_buttons', $table_buttons);
}

$smarty->assign('js_code', 'js/injections/supplier.orders.'.(_DEVEL ? '' : 'min.').'js');

include 'utils/get_table_html.php';


