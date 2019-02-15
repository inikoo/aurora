<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 November 2018 at 15:27:08 GMT+8,, Kuala Lumpur, Malaysia
 Copyright (c) 2018 Inikoo

 Version 3

*/

$tab     = 'warehouse.returns';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'returns';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array('label' => _('Number')),
);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


include 'utils/get_table_html.php';

?>
