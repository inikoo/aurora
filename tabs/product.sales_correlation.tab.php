<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 05-08-2019 15:30:06 MYT Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'product_sales_correlations';
$ar_file = 'ar_products_tables.php';
$tipo    = 'product_correlations';

$default = $user->get_tab_defaults($tab);




$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    )

);

$table_filters = array(
    'code'         => array(
        'label' => _('Name'),
    ),


);

$parameters = array(
    'parent'     => 'product',
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';



