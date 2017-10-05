<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 October 2017 at 22:25:25 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'warehouse.leakages.transactions';
$ar_file = 'ar_warehouse_tables.php';
$tipo    = 'leakages_transactions';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),
  

);

$table_filters = array(
    'name'         => array(
        'label' => _('Reference'),
        'title' => _('Reference')
    )

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


?>
