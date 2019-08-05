<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2018 at 14:18:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'store.current_shipping_zones';
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
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Name')
    )

);

$parameters = array(
    'parent'     => 'shipping_zone_schema',
    'parent_key' => $state['_object']->properties['current_shipping_zone_schema'],
    'store_key'     => $state['key'],
    'store_currency'     => $state['_object']->get('Store Currency Code')

);



$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New shipping zone'),
    'reference' => "store/".$state['key']."/shipping_zone_schema/".$state['_object']->properties['current_shipping_zone_schema']."/new"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
