<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 15:10:19 CETT, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/


/**
 * @var $delivery_note \DeliveryNote
 */
$delivery_note=$state['_object'];

if($delivery_note->get('Delivery Note State')=='Cancelled'){
    $tipo    = 'delivery_note_cancelled.items';
    $tab     = 'delivery_note_cancelled.items';
    $ar_file = 'ar_orders_tables.php';
}else{
    $tipo    = 'delivery_note.items';
    $tab     = 'delivery_note.items';
    $ar_file = 'ar_orders_tables.php';
}


$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Overview'),
    )

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

$smarty->assign(
    'table_metadata',
                        json_encode(
                            array('parent'     => $state['object'],
                                  'parent_key' => $state['key']
                            )
                        )

);
$smarty->assign('dn', $delivery_note);


$warehouse=get_object('warehouse',$delivery_note->get('Delivery Note Warehouse Key'));

$table_buttons   = array();
$smarty->assign('table_buttons', $table_buttons);

include('utils/get_table_html.php');

