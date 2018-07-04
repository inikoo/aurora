<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2018 at 19:08:54 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$parameters = array(
    'parent'     =>$state['object'],
    'parent_key' => $state['key'],

);


  $tab     = 'email_campaign_type.mailshots';
        $ar_file = 'ar_mailshot_tables.php';
        $tipo    = 'mailshots';



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
        'title' => _('name')
    )

);



$table_buttons   = array();

include 'utils/get_table_html.php';


?>
