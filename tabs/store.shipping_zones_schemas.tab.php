<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 December 2018 at 15:07:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'store.shipping_zones_schemas';
$ar_file = 'ar_products_tables.php';
$tipo    = 'shipping_zones_schemas';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
  

);

$table_filters = array(
    'label'         => array(
        'label' => _('Label'),
        'title' => _('Label')
    )

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key']

);


$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New shipping zone schema'),
    'reference' => "store/".$state['key']."/shipping_zone_schema/new"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
