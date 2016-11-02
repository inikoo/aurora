<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 May 2016 at 10:36:32 CEST, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'data_sets.uploads';
$ar_file = 'ar_account_tables.php';
$tipo    = 'uploads';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include('utils/get_table_html.php');


?>
