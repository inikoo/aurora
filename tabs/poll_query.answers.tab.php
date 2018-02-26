<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:22 February 2018 at 00:30:30 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'customers_poll.query.answers';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'poll_query_answers';

$default = $user->get_tab_defaults($tab);

$table_views   = array();
$table_filters = array(
    'code' => array(
        'label' => _('Answer'),
        'title' => _('Answer')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include('utils/get_table_html.php');


?>
