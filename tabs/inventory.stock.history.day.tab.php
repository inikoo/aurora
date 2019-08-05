<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 December 2017 at 14:02:37 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'inventory.stock.history.day';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'stock.history.day';

$default = $user->get_tab_defaults($tab);

$table_views = array(
    'overview' => array(
        'label' => _('Stock'),
        'title' => _('Stock')
    ),
    '1_year'    => array(
        'label' => _('1 year'),
        'title' => _('1 year')
    ),

);
$table_filters = array(

    'reference'         => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),



);




$parameters    = array(
    'parent'     => 'day',
    'parent_key' =>$state['key'],
    'warehouse_key' =>$state['current_warehouse'],
);

$table_buttons   = array();

$smarty->assign('table_buttons', $table_buttons);



include 'utils/get_table_html.php';

$html = $html;

?>
