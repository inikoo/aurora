<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:27 16 September 2015 17:50:04 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'store.departments';
$ar_file = 'ar_products_tables.php';
$tipo    = 'departments';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'sales'    => array(
        'label' => _('Sales'),
        'title' => _('Sales')
    ),

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Department code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Department name')
    ),

);

$parameters = array(
    'parent'     => 'store',
    'parent_key' => $state['key'],

);


include('utils/get_table_html.php');


?>
