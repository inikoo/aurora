<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2015 at 12:45:10 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'website.user.pageviews';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'pageviews';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'page' => array(
        'label' => _('Page'),
        'title' => _('Page code')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key']
);


include('utils/get_table_html.php');


?>
