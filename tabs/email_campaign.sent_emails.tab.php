<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 10 May 2018 at 10:05:40 CEST, Mijas Costa, Spain
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     =>$state['object'],
    'parent_key' => $state['key'],

);


  $tab     = 'email_campaign.sent_emails';
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
    'name'         => array(
        'label' => _('Name'),
        'title' => _('Customer name')
    ),
    'email'        => array(
        'label' => _('Email'),
        'title' => _('Customer email')
    ),
    'company_name' => array(
        'label' => _('Company name'),
        'title' => _('Company name')
    ),
    'contact_name' => array(
        'label' => _('Contact name'),
        'title' => _('Contact name')
    )

);



$table_buttons   = array();

include 'utils/get_table_html.php';


?>
