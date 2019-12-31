<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 February 2019 at 02:03:49 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$tab     = 'marketing_emails';
$ar_file = 'ar_mailroom_tables.php';
$tipo    = 'marketing_emails';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'type' => array('label' => _('Type'))

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include('utils/get_table_html.php');



