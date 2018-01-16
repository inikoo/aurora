<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 January 2018 at 19:15:59 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'customers_poll.queries';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'poll_queries';

$default = $user->get_tab_defaults($tab);

$table_views   = array();
$table_filters = array(
    'query' => array(
        'label' => _('Query'),
        'title' => _('Query')
    ),

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


include('utils/get_table_html.php');


?>
