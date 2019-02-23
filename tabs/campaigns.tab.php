<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2016 at 22:23:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'campaigns';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'campaigns';

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
    'parent'     => 'store',
    'parent_key' => $state['parent_key'],

);


$table_buttons   = array();

/*

$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New campaign'),
    'reference' => "campaigns/".$state['parent_key']."/new"
);
$smarty->assign('table_buttons', $table_buttons);

*/

$smarty->assign('title', _("Offer's categories").' <span class=\'id\'>'.$state['_parent']->get('Code').'</span>');
$smarty->assign('view_position', ' <span onclick=\"change_view(\'stores\')\">'._('Stores').'</span>  <i class=\"fa fa-angle-double-right separator\"></i>  <i class=\"fal fa-bullhorn\"></i> '._('Campaigns').' <span class=\'id\'>'.$state['_parent']->get('Code').'</span>');


include 'utils/get_table_html.php';


?>
