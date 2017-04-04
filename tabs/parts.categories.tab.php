<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 June 2016 at 13:49:17 CEST, Train (Nottingham-Sheffield) 
 Copyright (c) 2015, Inikoo

 Version 3

*/


if($account->get('Account Warehouses')==0){

    $html='<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'),'<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';
    return;
}


$tab     = 'parts.categories';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'categories';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'label' => array(
        'label' => _('Label'),
        'title' => _('Category label')
    ),
    'code'  => array(
        'label' => _('Code'),
        'title' => _('Category code')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
    'subject'    => 'part',
);

$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New category'),
    'reference' => "inventory/category/new"
);

$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
