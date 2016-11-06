<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:22 November 2015 at 01:12:28 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'staff.user.api_key.requests';
$ar_file = 'ar_users_tables.php';
$tipo    = 'api_requests';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include('utils/get_table_html.php');

?>
