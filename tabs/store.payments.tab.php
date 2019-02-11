<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 December 2018 at 16:00:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'store.payments';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'payments';




$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),

);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);



$smarty->assign('title', _('Payments').' <span class=\"id\" title=\"'.$state['_parent']->get('Name').'\" >'.$state['_parent']->get('Code').'</span>');

$smarty->assign('view_position','<span onclick=\"change_view(\'payments/per_store\')\"><i class=\"fal fa-layer-group\"></i> '._('Payments per store').'</span><i class=\"fa fa-angle-double-right separator\"></i>    <span onclick=\"change_view(\'payments/'.$state['_parent']->id.'\')\">'._('Payments').'  <span id=\"id\">('.$state['_parent']->get('Code').')</span></span>');


include('utils/get_table_html.php');


?>
