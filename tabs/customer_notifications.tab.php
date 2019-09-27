<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 February 2019 at 16:41:43 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$tab     = 'customer_notifications';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'customer_notifications';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'type' => array('label' => _('Type'))

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include('utils/get_table_html.php');



$html=$html;


