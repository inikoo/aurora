<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  18:13 pm Sat, 9 January 2021 (MYT) Kuala Lumpur Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/
$tab     = 'consignment.parts';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'consignment_parts';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'reference' => array(
        'label' => _('Reference')
            )

);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';


