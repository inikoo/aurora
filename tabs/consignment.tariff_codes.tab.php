<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  18:14 pm Sat, 9 January 2021 (MYT) Kuala Lumpur Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/
$tab     = 'consignment.tariff_codes';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'consignment_tariff_codes';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'tariff_code' => array(
        'label' => _('Tariff code')
            ),


);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';


