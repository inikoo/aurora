<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 06-09-2019 21:49:06 MYT, Kuala Lumpur Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/




$tab     = 'inventory.feedback_per_part_family';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'feedback_per_part_family';

$default = $user->get_tab_defaults($tab);


$table_views = array(


);





$table_filters = array(
    'reference' => array(
        'label' => _('Code'),
        'title' => _('Category code')
    ),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


$table_buttons = array();


include 'utils/get_table_html.php';



