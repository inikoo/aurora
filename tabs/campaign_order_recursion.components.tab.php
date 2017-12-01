<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2017 at 15:00:36 GMT+7, Bangkok , Thailand
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'campaign_order_recursion.components';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'campaign_order_recursion_components';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(

    'name' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();


$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New allocance'),
    'reference' => "deal/".$state['parent_key']."/".$state['key']."/deal/allowance"
);
$smarty->assign('table_buttons', $table_buttons);


include 'utils/get_table_html.php';


?>
