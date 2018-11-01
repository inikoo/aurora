<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  1 November 2018 at 17:48:35 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'sales_representative.invoices_group_by_customer';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'invoices_group_by_customer';

$default = $user->get_tab_defaults($tab);

$table_views = array();


$table_filters = array(
    'customer' => array(
        'label' => _('Customer'),
        'title' => _('Customer name')
    ),
   

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';


?>
