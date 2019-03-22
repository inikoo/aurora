<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 March 2019 at 09:49:36 GMT+8, Kuala Lumpur, Malysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'customer.credit_blockchain';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'credit_blockchain';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    ),
);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include('utils/get_table_html.php');

?>
