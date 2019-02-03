<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 February 2019 at 16:12:10 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/

$tab     = 'deleted_invoice.items';
$ar_file = 'ar_orders_tables.php';

if( $state['_object']->get('Invoice Type')=='Refund'){
    $tipo    = 'deleted_invoice.items';

}else{
    $tipo    = 'deleted_invoice.items';

}

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Description'),
        'title' => _('Description')
    ),


);

$table_filters = array(

  

);

$parameters = array(
    'parent'     => $state['object'],
    'parent_key' => $state['key'],

);


include('utils/get_table_html.php');


?>
