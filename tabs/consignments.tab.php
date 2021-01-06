<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  2:03 pm Wednesday, 6 January 2021 (MYT)Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/
$tab     = 'consignments';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'consignments';




if($account->get('Account Warehouses')==0){

    $html='<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'),'<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';
    return;
}

if($account->get('Account Stores')==0){

    $html='<div style="padding:20px">'.sprintf(_('There are not stores, create one %s'),'<span class="marked_link" onClick="change_view(\'/store/new\')" >'._('here').'</span>').'</div>';
    return;
}


$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(

    'number'   => array(
        'label' => _('Number'),
        'title' => _('Consignment number')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include 'utils/get_table_html.php';

