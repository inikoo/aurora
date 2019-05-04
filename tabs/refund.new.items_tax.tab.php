<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 03-05-2019 09:54:09  GMT+2 , Tranva, Slovakia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'refund.new.items_tax';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'refund.new.items_tax';

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

$smarty->assign('table_top_template', 'new_refund_tax.tpl');


include('utils/get_table_html.php');


?>
