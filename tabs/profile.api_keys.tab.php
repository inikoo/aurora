<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 May 2018 at 09:32:25 BST, Sheffield, UK
 Copyright (c) 2018, Inikoo

 Version 3

*/

$tab     = 'staff.user.api_keys';
$ar_file = 'ar_users_tables.php';
$tipo    = 'profile_api_keys';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

$table_buttons   = array();
$table_buttons[] = array(
    'icon'      => 'plus',
    'title'     => _('New API key'),
    'reference' => "profile/new/api_key"
);
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');


