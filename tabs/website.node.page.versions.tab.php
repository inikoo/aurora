<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 31 May 2016 at 20:07:24 CEST, Mijas Costa , Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'website.node.pages';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'pages';

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
