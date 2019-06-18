<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 November 2017 at 12:14:41 GMT+8, Legian Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'refund.new.items';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'refund.new.items';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Description'),
        'title' => _('Description')
    ),
   

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Product code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Product name')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();

$smarty->assign('table_buttons', $table_buttons);



include('utils/get_table_html.php');


?>
