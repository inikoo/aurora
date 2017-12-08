<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 8 December 2017 at 12:50:09 CET, Mijas Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3

*/


$tab     = 'parts_with_unknown_location.wget';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'warehouse.parts_with_unknown_location';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Reference')),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


include 'utils/get_table_html.php';


?>
