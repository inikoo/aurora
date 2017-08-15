<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2017 at 10:13:26 CEST, Tranava, Slovakia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab     = 'delivery_note.fast_track_packing';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'delivery_note.fast_track_packing';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Overview'),
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

$smarty->assign(
    'table_metadata', base64_encode(
                        json_encode(
                            array('parent'     => $state['object'],
                                  'parent_key' => $state['key']
                            )
                        )
                    )
);
$smarty->assign('dn', $state['_object']);


$warehouse=get_object('warehouse',$state['_object']->get('Delivery Note Warehouse Key'));




$table_buttons   = array();



    $table_buttons[] = array(
        'icon'     => 'sign-out fa-flip-horizontal',
        'title'    => _('Exit'),
        'id'       => 'exit_fast_track_packing',


    );





$smarty->assign('table_buttons', $table_buttons);



include('utils/get_table_html.php');


?>
