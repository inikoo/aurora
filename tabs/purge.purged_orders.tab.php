<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2018 at 23:47:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'purge.purged_orders';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'purged_orders';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'number' => array(
        'label' => _('Number'),
        'title' => _('Order number')
    ),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include('utils/get_table_html.php');

?>
