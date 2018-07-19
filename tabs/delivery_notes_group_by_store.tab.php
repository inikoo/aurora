<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 July 2018 at 23:27:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'delivery_notes_group_by_store';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'delivery_notes_group_by_store';

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
