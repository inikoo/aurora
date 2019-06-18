<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18-06-2019 15:43:14 MYT Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'webpage_type.in_process_webpages';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'in_process_webpages';

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



