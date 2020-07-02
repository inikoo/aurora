<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2016 at 10:38:48 GMT+8, Kuala Lumpur, Mlaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if($account->get('Account Warehouses')==0){

    $html='<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'),'<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';
    return;
}


$tab     = 'inventory.in_process_parts';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'in_process_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

if(!isset($_SESSION['inventory_show_production'])){
    $_SESSION['inventory_show_production']='No';
}

if(isset($_SESSION['table_state'][$tab]['show_production'])){
    $show_production=$_SESSION['table_state'][$tab]['show_production'];
}else{

    if(isset($_SESSION['inventory_show_production'])){
        $show_production=$_SESSION['inventory_show_production'];
    }else{
        $show_production=$default['show_production'];

    }
}

$smarty->assign('show_production',$show_production);
$smarty->assign('table_top_template', 'control.inventory.tpl');


include 'utils/get_table_html.php';



