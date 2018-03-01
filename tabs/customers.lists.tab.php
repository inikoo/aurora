<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 September 2015 12:39:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'customers.lists';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'lists';

$default = $user->get_tab_defaults($tab);

$table_views   = array();
$table_filters = array(
    'name' => array(
        'label' => _('Name'),
        'title' => _('List name')
    ),

);

$parameters = array(
    'parent'     => 'store',
    'parent_key' => $state['parent_key'],

);



$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New customer'),
    'reference' => "customers/".$state['parent_key']."/lists/new"
);
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


?>
