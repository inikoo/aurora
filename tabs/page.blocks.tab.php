<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 June 2016 at 11:23:27 CEST, Mijas Costa, Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'page.blocks';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'blocks';

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
