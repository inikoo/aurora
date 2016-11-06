<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2015 at 17:41:07 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'account.users';
$ar_file = 'ar_users_tables.php';
$tipo    = 'user_categories';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => '',
    'parent_key' => '',
);

include('utils/get_table_html.php');


?>
