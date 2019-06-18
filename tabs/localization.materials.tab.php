<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 08-05-2019 22:43:45 CEST, Tranava, Slocakia
 Copyright (c) 2019, Inikoo

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
