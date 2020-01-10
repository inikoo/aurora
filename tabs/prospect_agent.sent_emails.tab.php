<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 September 2018 at 11:29:35 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     => 'prospect_agent',
    'parent_key' => $state['key'],

);


$tab     = 'prospect_agent.sent_emails';
$ar_file = 'ar_mailroom_tables.php';
$tipo    = 'subject_sent_emails';


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'subject' => array(
        'label' => _('Subject'),
        'title' => _('Subject')
    ),


);


$table_buttons = array();

include 'utils/get_table_html.php';


?>
