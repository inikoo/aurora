<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  9:53 pm Wednesday, 6 January 2021 (MYT) Kuala Lumpur Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/
$tab     = 'consignment.delivery_notes';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'delivery_notes';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'customer' => array(
        'label' => _('Customer')
            ),
    'number'   => array(
        'label' => _('Number')
            ),

);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';


