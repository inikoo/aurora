<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 20 February 2017 at 12:52:32 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$tab     = 'website.webpage.types';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'webpage_types';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

include('utils/get_table_html.php');

?>
