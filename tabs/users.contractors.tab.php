<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 June 2016 at 19:27:26 BST, Heathrow Airport, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'users.contractors';
$ar_file = 'ar_users_tables.php';
$tipo    = 'contractors';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array('label' => _('Overview')),
    'privileges'      => array('label' => _('Permissions')),
    'groups'      => array('label' => _('Groups')),
    'weblog'      => array('label' => _('Syslog')),

);

$table_filters = array(
    'handle' => array('label' => _('Handle')),
    'name'   => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => $state['parent_key'],

);


include('utils/get_table_html.php');


?>
