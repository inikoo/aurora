<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 20 Jul 2021 20:35:22 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

/** @var array $state */
/** @var \User $user */


$tab     = 'customers.registrations';
$ar_file = 'ar_customers_tables.php';
$tipo    = 'customers_registrations';

$default = $user->get_tab_defaults($tab);


$table_views = array();

$table_filters = array();

$parameters = array(
    'parent'     => $state['parent'],
    'parent_key' => $state['parent_key'],
);


include 'utils/get_table_html.php';



