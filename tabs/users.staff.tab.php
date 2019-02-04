<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 10:12:03 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'users.staff';
$ar_file = 'ar_users_tables.php';
$tipo    = 'staff';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'   => array('label' => _('Overview')),
    'privileges' => array('label' => _('Permissions')),
    'groups'     => array('label' => _('Groups')),
    'weblog'     => array('label' => _('Syslog')),

);

$table_filters = array(
    'handle' => array(
        'label' => _('Handle'),
        'title' => _('User handle')
    ),
    'name'   => array(
        'label' => _('Name'),
        'title' => _('User name')
    ),

);

$parameters = array(
    'parent'     => 'account',
    'parent_key' => $state['parent_key'],

);


include('utils/get_table_html.php');


?>
