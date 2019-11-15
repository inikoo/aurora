<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:   15 November 2019  11:14::50  +0100, Malaga, Spain
 Copyright (c) 2019, Inikoo

 Version 3

*/



$tab     = 'inventory.parts_forced_not_for_sale_on_website.wget';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'parts_forced_not_for_sale_on_website';

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



