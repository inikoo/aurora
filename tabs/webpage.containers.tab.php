<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  28 November 2019  10:16::26  +0100, Malaga, Spain
 Copyright (c) 2019, Inikoo

 Version 3

*/


$tab     = 'webpage.containers';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'webpage_containers';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code'  => array('label' => _('Code')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include('utils/get_table_html.php');



