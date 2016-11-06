<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 September 2015 16:32:32 GMT+7, Bangkok, Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'orders_index';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'orders_index';

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


include('utils/get_table_html.php');


?>
