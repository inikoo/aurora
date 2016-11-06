<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 October 2015 at 12:16:42 BSTs, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'marketing_server';
$ar_file = 'ar_marketing_tables.php';
$tipo    = 'marketing_server';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Store code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Store name')
    ),

);

$parameters = array(
    'parent'     => '',
    'parent_key' => '',
);


include('utils/get_table_html.php');


?>
