<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 February 2019 at 23:55:54 GMT+8
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'users';
$ar_file = 'ar_users_tables.php';
$tipo    = 'users';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
    'handle' => array('label' => _('Handle')),
);

$parameters = array(
    'parent'     => '',
    'parent_key' => '',
);



$smarty->assign('title', _('Users').' ('._('All').')');
$smarty->assign('view_position', '<i class=\"fal fa-users-class\"></i> '._('Users'));


include('utils/get_table_html.php');


