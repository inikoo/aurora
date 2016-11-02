<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 15:10:19 CETT, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$tab     = 'delivery_note.items';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'delivery_note.items';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Description'),
        'title' => _('Description')
    ),
    'tariff_codes' => array(
        'label' => _('Tariff Codes'),
        'title' => _('Tariff Codes')
    ),

);

$table_filters = array(
    'code' => array(
        'label' => _('Code'),
        'title' => _('Product code')
    ),
    'name' => array(
        'label' => _('Name'),
        'title' => _('Product name')
    ),

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include('utils/get_table_html.php');


?>
