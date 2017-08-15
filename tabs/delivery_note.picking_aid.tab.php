<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2017 at 10:13:26 CEST, Tranava, Slovakia
 Copyright (c) 2015, Inikoo

 Version 3

*/



$tab     = 'delivery_note.items';
$ar_file = 'ar_orders_tables.php';
$tipo    = 'delivery_note.items';

$default = $user->get_tab_defaults($tab);


$table_views = array(
    'overview'     => array(
        'label' => _('Overview'),
    ),

    'picking_aid_offline' => array(
        'label' => _('Picking Aid').' *',
    ),

    'picking_aid' => array(
        'label' => _('Picking Aid'),
    ),
    'packing_aid' => array(
        'label' => _('Packing Aid'),
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


if($warehouse->get('Warehouse Delivery Note Processing Block')=='offline'){

    $table_buttons[] = array(
        'icon'     => 'hand-lizard-o  fa-rotate-270',
        'title'    => _('Pick delivery'),
        'id'       => 'pick_offline_delivery',
        'class'    => 'items_operation'.($state['_object']->get('State Index')>0 and  $state['_object']->get('State Index')<80   ? ' hide' : ''),


    );



    $smarty->assign('table_top_template', 'delivery_note.options.offline.tpl');

}else {

    $table_buttons[] = array(
        'icon'     => 'hand-lizard-o  fa-rotate-270',
        'title'    => _('Pick delivery'),
        'id'       => 'pick_real_time_delivery',
        'class'    => 'items_operation'.($state['_object']->get('State Index')>0 and  $state['_object']->get('State Index')<80   ? ' hide' : ''),


    );


    $smarty->assign('table_top_template', 'delivery_note.options.tpl');

}


$smarty->assign('table_buttons', $table_buttons);



include('utils/get_table_html.php');


?>
