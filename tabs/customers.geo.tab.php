<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 January 2018 at 15:11:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/
/** @var array $state */
/** @var \User $user */


$tab     = 'customers.geo';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'customers_geographic_distribution';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'country'         => array(
        'label' => _('Country'),
        'title' => _('Country name')
    )

);

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],

);



include 'utils/get_table_html.php';

