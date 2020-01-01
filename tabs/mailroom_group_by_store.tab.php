<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  29 December 2019  18:17::15  +0800, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if($account->get('Account Stores')==0){

    $html='<div style="padding:20px">'.sprintf(_('There are not stores, create one %s'),'<span class="marked_link" onClick="change_view(\'/store/new\')" >'._('here').'</span>').'</div>';
    return;
}


$tab     = 'mailroom_group_by_store';
$ar_file = 'ar_mailroom_tables.php';
$tipo    = 'mailroom_group_by_store';

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



