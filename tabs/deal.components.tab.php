<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 September 2017 at 12:37:38 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'deal.compnents';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'compnents';

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
/*
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New allocance'),
    'reference' => "deal/".$state['parent_key']."/".$state['key']."/deal/allowance"
);
$smarty->assign('table_buttons', $table_buttons);
*/

include 'utils/get_table_html.php';


?>
