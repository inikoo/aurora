<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2015 at 13:03:57 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'order.all_products';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'order.all_products';

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

$smarty->assign(
    'table_metadata',
                        json_encode(
                            array(
                                'parent'     => $state['object'],
                                'parent_key' => $state['key'],
                                'field'      => 'Order Quantity'
                            )
                        )

);



$smarty->assign(
    'js_code', 'js/injections/order.'.(_DEVEL ? '' : 'min.').'js'
);


include('utils/get_table_html.php');


?>
