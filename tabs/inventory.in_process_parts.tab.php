<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2016 at 10:38:48 GMT+8, Kuala Lumpur, Mlaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'inventory.in_process_parts';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'in_process_parts';

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
