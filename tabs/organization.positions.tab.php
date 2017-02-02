<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 February 2017 at 16:47:21 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'organization.positions';
$ar_file = 'ar_hr_tables.php';
$tipo    = 'positions';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include('utils/get_table_html.php');

?>
