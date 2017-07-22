<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 July 2017 at 17:36:26 CEST, Trnava , Slovakia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'part_family.products';
$ar_file = 'ar_products_tables.php';
$tipo    = 'products';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'    => array('label' => _('Overview')),
    'price'    => array('label' => _('Price')),

    'performance' => array('label' => _('Performance')),
    'sales'       => array('label' => _('Sales')),
    'sales_y'     => array('label' => _('Invoiced amount (Yrs)')),
    'sales_q'     => array('label' => _('Invoiced amount (Qs)')),

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

$table_buttons=array();
$table_buttons[] = array(
    'icon'  => 'edit',
    'title' => _("Edit products"),
    'id'    => 'edit_table'
);
$smarty->assign('table_buttons', $table_buttons);



include 'utils/get_table_html.php';


?>
