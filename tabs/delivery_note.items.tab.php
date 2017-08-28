<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 October 2015 at 15:10:19 CETT, Pisa-Milan (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/






if($state['_object']->get('Delivery Note State')=='Cancelled'){
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
$smarty->assign('table_buttons', $table_buttons);



include('utils/get_table_html.php');


?>
