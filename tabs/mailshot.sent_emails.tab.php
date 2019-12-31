<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 July 2018 at 00:18:38 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$tab     = 'mailshot.sent_emails';
$ar_file = 'ar_mailroom_tables.php';
$tipo    = 'sent_emails';

$smarty->assign('mailshot_type', $state['_object']->get('Email Campaign Type'));


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


