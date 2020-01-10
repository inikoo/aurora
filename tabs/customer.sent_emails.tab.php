<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2018 at 00:03:55 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$tab     = 'customer.sent_emails';
$ar_file = 'ar_mailroom_tables.php';
$tipo    = 'sent_emails';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'name'  => array(
        'label' => _('Name'),
        'title' => _('Customer name')
    ),
    'email' => array(
        'label' => _('Email'),
        'title' => _('Customer email')
    )

);


$table_buttons = array();

include 'utils/get_table_html.php';


?>
