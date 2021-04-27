<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 April 2021 at 22:20:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/


$tab     = 'packing_bands';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'packing_bands';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
  

);

$table_filters = array(
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Name')
    )

);

$parameters = array(
    'parent'     => 'warehouse',
    'parent_key' => $state['key']

);


$table_buttons   = array();
//$table_buttons[] = array(
//    'icon'      => 'plus',
//    'title'     => _('New shipping zone schema'),
//    'reference' => "store/".$state['key']."/shipping_zone_schema/new"
//);
//$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';



