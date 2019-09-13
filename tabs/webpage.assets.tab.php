<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13-09-2019 18:32:26 MYT, Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$tab     = 'webpage.assets';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'webpage_assets';

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



