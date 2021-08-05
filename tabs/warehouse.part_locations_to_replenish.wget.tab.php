<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 June 2017 at 12:26:32 GMT+7, Phuket, Thailand
 Copyright (c) 2017, Inikoo

 Version 3

*/

/** @var array $state */
/** @var User $user */

$tab     = 'warehouse.part_locations_to_replenish.wget';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'replenishments';

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

