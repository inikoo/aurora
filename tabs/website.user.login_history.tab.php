<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2015 at 12:27:01 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'website.user.login_history';
$ar_file = 'ar_users_tables.php';
$tipo    = 'login_history';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'ip' => array(
        'label' => _('Ip'),
        'title' => _('IP address')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key']
);


include('utils/get_table_html.php');


?>
