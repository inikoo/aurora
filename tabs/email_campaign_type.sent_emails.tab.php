<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2018 at 13:23:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);



if($state['_object']->get('Email Campaign Type Scope')=='User Notification'){
    $tab     = 'user_notification.sent_emails';
    $ar_file = 'ar_mailroom_tables.php';
    $tipo    = 'user_notification_sent_emails';
}else{

    $tab     = 'email_campaign_type.sent_emails';
    $ar_file = 'ar_mailroom_tables.php';
    $tipo    = 'sent_emails';

    if($state['_object']->get('Code')=='Invite' or $state['_object']->get('Code')=='Invite Mailshot') {
        $smarty->assign('recipient', 'prospect');
        $smarty->assign('recipient_label', _('Prospects'));
    }else{
        $smarty->assign('recipient', 'customer');
        $smarty->assign('recipient_label', _('Customers'));
    }

}


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


$table_buttons = array();



include 'utils/get_table_html.php';


?>
