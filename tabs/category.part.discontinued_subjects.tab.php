<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2016 at 10:18:45 GMT+8, Cyberjaya Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'category.part.discontinued_subjects';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'category_discontinued_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),

);


$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include 'utils/get_table_html.php';


?>
