<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 July 2016 at 20:24:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'deleted.supplier.order.items';
$ar_file = 'ar_suppliers_tables.php';
$tipo    = 'deleted.order.items';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array('label' => _('Description')),

);

$table_filters = array(
    'code' => array('label' => _('Code')),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);

include 'utils/get_table_html.php';


?>
