<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2016 at 11:13:12 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'suppliers.deliveries';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'deliveries';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array('label' => _('Number')),
);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

include('utils/get_table_html.php');

?>
