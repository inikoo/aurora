<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 January 2016 at 17:32:37 GMT+8, Penang, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'account.credits';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'account.credits';



if($account->get('Account Warehouses')==0){

    $html='<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'),'<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';
    return;
}

if($account->get('Account Stores')==0){

    $html='<div style="padding:20px">'.sprintf(_('There are not stores, create one %s'),'<span class="marked_link" onClick="change_view(\'/store/new\')" >'._('here').'</span>').'</div>';
    return;
}


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(
    'customer' => array(
        'label' => _('Customer'),
        'title' => _('Customer name')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


include('utils/get_table_html.php');


?>
