<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 November 2015 at 17:18:46 CET, Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'staff.user.api_keys';
$ar_file = 'ar_users_tables.php';
$tipo    = 'api_keys';

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
    'reference' => "account/".$state['object']."/".$state['key']."/new/api_key"
);
$smarty->assign('table_buttons', $table_buttons);


include('utils/get_table_html.php');

?>
