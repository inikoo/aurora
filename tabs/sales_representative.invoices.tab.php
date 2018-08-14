<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  14 August 2018 at 15:20:49 GMT+8, Legian, Bali, Indonesia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'sales_representative.invoices';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'invoices';

$default = $user->get_tab_defaults($tab);

$table_views = array();


$table_filters = array(
    'customer' => array(
        'label' => _('Customer'),
        'title' => _('Customer name')
    ),
    'number'   => array(
        'label' => _('Number'),
        'title' => _('Invoice number')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';


?>
