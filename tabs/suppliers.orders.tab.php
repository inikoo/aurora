<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 12:52:11 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if($account->get('Account Warehouses')==0){

    $html='<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'),'<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';
    return;
}

$tab     = 'suppliers.orders';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),
);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

include('utils/get_table_html.php');

?>
