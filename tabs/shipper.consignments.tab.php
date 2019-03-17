<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  16 March 2019 at 15:08:16 GMT+8, Sanur, Bali, Indonesia
 Copyright (c) 2019, Inikoo

 Version 3

*/
$tab     = 'shipper.consignments';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'consignments';

$default = $user->get_tab_defaults($tab);

$table_views = array();

$table_filters = array(
   
    'number'   => array(
        'label' => _('Number'),
        'title' => _('Delivery note number')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],
);


include 'utils/get_table_html.php';


?>
