<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 September 2018 at 13:41:09 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$tab     = 'order.sent_emails';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'order_sent_emails';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
  
);


$table_buttons = array();

include 'utils/get_table_html.php';


?>
