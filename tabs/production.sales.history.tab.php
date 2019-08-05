<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19-07-2019 17:21:18 MYT kUALA lUMPUR, mALAYSIA
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'supplier.sales.history';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'sales_history';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => 'supplier',
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';



