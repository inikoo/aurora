<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:16:42 BSTs, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


if($account->get('Account Stores')==0){

    $html='<div style="padding:20px">'.sprintf(_('There are not stores, create one %s'),'<span class="marked_link" onClick="change_view(\'/store/new\')" >'._('here').'</span>').'</div>';
    return;
}

$tab     = 'offers_group_by_store';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'offers_group_by_store';



$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Store code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Store name')
    ),

);

$parameters = array(
    'parent'     => '',
    'parent_key' => '',
);

$smarty->assign('table_class','with_totals');

include('utils/get_table_html.php');


