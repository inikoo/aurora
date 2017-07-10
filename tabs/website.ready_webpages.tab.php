<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:10 July 2017 at 17:36:38 GMT+8  Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'website.ready_webpages';
$ar_file = 'ar_websites_tables.php';
$tipo    = 'ready_webpages';

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


?>
