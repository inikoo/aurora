<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11:58 pm Tuesday, 30 June 2020 (MYT) Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


if($account->get('Account Manufacturers')==0){
    $html='<div style="padding:20px">'._('No production').'</div>';
    return;
}


$tab     = 'production.active_parts';
$ar_file = 'ar_production_tables.php';
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


include 'utils/get_table_html.php';



