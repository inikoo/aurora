<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2018 at 19:08:34 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$ar_file = 'ar_mailroom_tables.php';



if($state['_object']->get('Code')=='OOS Notification'){
    $tab     = 'oss_notification.next_recipients';
    $tipo    = 'oss_notification_next_recipients';
}elseif($state['_object']->get('Code')=='GR Reminder'){
    $tab     = 'gr_reminder.next_recipients';
    $tipo    = 'gr_reminder_next_recipients';
}else{

    $html=$state['_object']->get('Code');
    return;
}





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
        'title' => _('Name')
    ),
    'email' => array(
        'label' => _('Email'),
        'title' => _('Email')
    ),

);


$table_buttons = array();

include 'utils/get_table_html.php';


?>
