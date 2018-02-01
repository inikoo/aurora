<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 January 2018 at 17:45:56 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'staff.user.deleted_api_keys';
$ar_file = 'ar_users_tables.php';
$tipo    = 'deleted_api_keys';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons   = array();


include('utils/get_table_html.php');

?>
