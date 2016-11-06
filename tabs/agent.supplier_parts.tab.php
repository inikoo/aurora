<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 April 2016 at 13:24:33 GMT+8, Lovina, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$tab     = 'agent.supplier_parts';
$ar_file = 'ar_inventory_tables.php';
$tipo    = 'supplier_parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
    'parts'    => array(
        'label' => _('Inventory Part'),
        'title' => _('Part details')
    ),
    'reorder'  => array('label' => _('Reorder')),

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
