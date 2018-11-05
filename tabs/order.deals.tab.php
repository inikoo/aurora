<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 November 2018 at 01:14:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'order.deals';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'order.deals';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(

    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$smarty->assign('order', $state['_object']);

$table_buttons   = array();


include 'utils/get_table_html.php';


?>
