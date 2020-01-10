<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2018 at 19:32:16 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$tab     = 'email_tracking.events';
$ar_file = 'ar_mailroom_tables.php';
$tipo    = 'email_tracking_events';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
 



);

$table_filters = array(
  

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


$table_buttons   = array();

include 'utils/get_table_html.php';


?>
