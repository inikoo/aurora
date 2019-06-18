<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:07-06-2019 17:51:05 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'credits_group_by_store';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'credits_group_by_store';

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


$smarty->assign('account_currency_code', $account->get('Account Currency'));

$smarty->assign('title', _('credits per store'));
$smarty->assign('view_position', '<i class=\"fal fa-layer-group\"></i> '._('credits per store'));



include('utils/get_table_html.php');



