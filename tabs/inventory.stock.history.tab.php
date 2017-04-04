<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 July 2016 at 11:46:08 GMT+8, Kaula Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


if($account->get('Account Warehouses')==0){

    $html='<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'),'<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';
    return;
}

$tab     = 'inventory.stock.history';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'inventory_stock_history';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'note' => array(
        'label' => _('Note'),
        'title' => _('Note')
    ),

);


$parameters = array(
    'parent'     => 'account',
    'parent_key' => 1,

);

include 'utils/get_table_html.php';


?>
