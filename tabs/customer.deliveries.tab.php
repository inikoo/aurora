<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 July 2021 17:49GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016 Inikoo

 Version 3

*/
/** @var array $state */
/** @var \User $user */
/** @var \Smarty $smarty */

$tab     = 'customer.deliveries';
$ar_file = 'ar_fulfilment_tables.php';
$tipo    = 'deliveries';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array('label' => _('Number')),
);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = [];
//$table_buttons[] = array(
//    'icon'  => 'plus',
//    'title' => _('New delivery'),
//    'id'    => 'new_customer_delivery',
//    'attr'  => array(
//        'parent'        => $state['object'],
//        'parent_key'    => $state['key'],
//        'warehouse_key' => $state['parent_key'],
//
//    )
//);

$smarty->assign('table_buttons', $table_buttons);

include 'utils/get_table_html.php';


