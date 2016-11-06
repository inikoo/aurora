<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 August 2016 at 17:50:18 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'data_sets.materials';
$ar_file = 'ar_account_tables.php';
$tipo    = 'materials';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include('utils/get_table_html.php');


?>
