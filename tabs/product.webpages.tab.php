<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29-04-2019 15:41:40 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


$tab     = 'product.webpages';
$ar_file = 'ar_products_tables.php';
$tipo    = 'webpages';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array(
        'label' => _('Overview'),
        'title' => _('Overview')
    ),


);

$table_filters = array(
    'code'         => array(
        'label' => _('Code'),
        'title' => _('Webpage code')
    ),


);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include 'utils/get_table_html.php';


