<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 December 2017 at 14:52:15 GMT+7, Bangkok, Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'payments_group_by_store';
$ar_file = 'ar_accounting_tables.php';
$tipo    = 'payments_group_by_store';

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



$smarty->assign('title', _('Payments per store'));
$smarty->assign('view_position', '<i class=\"fal fa-layer-group\"></i> '._('Payments per store'));



include('utils/get_table_html.php');


?>
