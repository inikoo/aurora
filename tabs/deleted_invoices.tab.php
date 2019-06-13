<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  12-06-2019 22:52:55 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'deleted_invoices';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'deleted_invoices';



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
        'label' => _('Public ID'),
        'title' => _('Invoice public Id')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);






include 'utils/get_table_html.php';



