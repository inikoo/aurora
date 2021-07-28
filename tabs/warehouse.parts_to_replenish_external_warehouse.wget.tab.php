<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17:45:01 MYT Monday, 13 July 2020 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/
/** @var array $state */
/** @var \User $user */

$tab     = 'warehouse.parts_to_replenish_external_warehouse.wget';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'external_warehouse_replenishes';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array(
    'reference' => array('label' => _('Part reference')),

);


$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);


include 'utils/get_table_html.php';


