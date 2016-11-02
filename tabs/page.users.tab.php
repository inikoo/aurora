<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2015 at 11:02:29 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'page.users';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'users';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'handle'   => array(
        'label' => _('Handle'),
        'title' => _('User Handle')
    ),
    'customer' => array(
        'label' => _('Customer'),
        'title' => _('Customer Name')
    )


);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


?>
