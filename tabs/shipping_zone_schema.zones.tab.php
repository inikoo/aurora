<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23-07-2019 18:55:15 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$tab     = 'shipping_zone_schema.zones';
$ar_file = 'ar_products_tables.php';
$tipo    = 'shipping_zones';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'usage' => array(
        'label' => _('Usage'),
        'title' => _('Usage')
    ),

);

$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('Name')
    )

);

$parameters = array(
    'parent'         => 'shipping_zone_schema',
    'parent_key'     => $state['_object']->id,
    'store_key'      => $state['store']->id,
    'store_currency' => $state['store']->get('Store Currency Code')

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New shipping zone'),
    'reference' => "store/".$state['key']."/shipping_zone_schema/".$state['_object']->id."/new"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



