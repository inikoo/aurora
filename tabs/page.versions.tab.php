<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2016 at 13:08:16 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'page.versions';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'versions';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code'  => array('label' => _('Code')),
    'title' => array('label' => _('Name')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include('utils/get_table_html.php');


?>
