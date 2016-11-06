<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 August 2016 at 12:58:45 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'material.parts';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array('label' => _('Overview')),
    'sales'    => array('label' => _('Sales')),

);

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
