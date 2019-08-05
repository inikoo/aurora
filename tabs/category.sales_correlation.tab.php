<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 04-08-2019 12:06:58 MYT Kuala Lumpur Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$tab     = 'category_sales_correlations';
$ar_file = 'ar_products_tables.php';
$tipo    = 'category_correlations';

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
    'parent'     => 'product_category',
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';



