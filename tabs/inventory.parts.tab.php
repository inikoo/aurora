<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 September 2015 18:22:51 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


if($account->get('Account Warehouses')==0){

    $html='<div style="padding:20px">'.sprintf(_('Warehouse missing, set it up %s'),'<span class="marked_link" onClick="change_view(\'/warehouse/new\')" >'._('here').'</span>').'</div>';
    return;
}


$tab     = 'inventory.parts';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'active_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array('label' => _('Overview')),
    'performance'  => array('label' => _('Performance')),
    'stock'        => array('label' => _('Stock')),
    'sales'        => array('label' => _('Revenue')),
    'dispatched_q' => array('label' => _('Dispatched (Qs)')),
    'dispatched_y' => array('label' => _('Dispatched (Yrs)')),
    'revenue_q'    => array('label' => _('Revenue (Qs)')),
    'revenue_y'    => array('label' => _('Revenue (Yrs)')),

);


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


$table_buttons = array();

/*
$table_buttons[]=array('icon'=>'plus', 'title'=>_('New part'), 'reference'=>"part/new");
$smarty->assign('table_buttons', $table_buttons);
$smarty->assign('upload_file', array(
'tipo'=>'upload_objects',
'parent'=>'account',
'parent_key'=>1,
'object'=>'part',
'label'=>_("Upload parts")

));
*/
include 'utils/get_table_html.php';


?>
