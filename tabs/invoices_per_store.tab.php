<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  19 December 2018 at 23:52:07 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'invoices_per_store';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'invoices_per_store';




if($account->get('Account Stores')==0){

    $html='<div style="padding:20px">'.sprintf(_('There are not stores, create one %s'),'<span class="marked_link" onClick="change_view(\'/store/new\')" >'._('here').'</span>').'</div>';
    return;
}

$default = $user->get_tab_defaults($tab);



$table_views = array();

$table_filters = array(
    'code' => array(
        'label' => _('Store'),
        'title' => _('Store code')
    ),


);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);



$smarty->assign('title', _('Invoices per store'));
$smarty->assign('view_position', '<i class=\"fal fa-layer-group\"></i> '._('Invoices per store'));

include 'utils/get_table_html.php';


?>
