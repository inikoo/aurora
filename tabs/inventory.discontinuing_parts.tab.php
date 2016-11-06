<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 August 2016 at 14:32:01 GMT+8, Kuta, 
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'inventory.discontinuing_parts';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'discontinuing_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);

include 'utils/get_table_html.php';


?>
