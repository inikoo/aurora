<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 December 2015 at 11:46:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'invoices_server.categories';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'invoice_categories';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'label' => array(
        'label' => _('Label'),
        'title' => _('Category label')
    ),
    'code'  => array(
        'label' => _('Code'),
        'title' => _('Category code')
    ),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => $state['parent_key'],
    'subject'    => 'invoice',
);


include('utils/get_table_html.php');


?>
