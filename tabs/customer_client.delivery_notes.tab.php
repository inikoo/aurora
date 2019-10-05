<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  Sat 5 Oct 2019 21:22:41 +0800 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/
$tab     = 'customer_client.delivery_notes';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'delivery_notes';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(

    'number' => array(
        'label' => _('Number'),
        'title' => _('Delivery note number')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';



