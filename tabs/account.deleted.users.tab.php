<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 July 2016 at 11:44:43 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'account.deleted.users';
$ar_file = 'ar_users_tables.php';
$tipo    = 'deleted_users';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'privilegies' => array('label' => _('Overview')),


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
    'parent_key' => 1,

);


include('utils/get_table_html.php');


?>
