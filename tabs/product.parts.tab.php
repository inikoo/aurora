<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2016 at 14:16:17 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$tab     = 'product.parts';
$ar_file = 'ar_products_tables.php';
$tipo    = 'parts';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview' => array('label' => _('Overview')),

    'stock' => array('label' => _('Stock')),


);

$table_filters = array(
    'reference' => array(
        'label' => _('Reference'),
        'title' => _('Part reference')
    ),


);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include('utils/get_table_html.php');


?>
