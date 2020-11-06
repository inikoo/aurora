<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 August 2017 at 10:13:26 CEST, Tranava, Slovakia
 Copyright (c) 2015, Inikoo

 Version 3

*/


if(empty($state['metadata']['dn_key'])){
    $html='';
}else {

    $delivery_note = get_object('Delivery_Note', $state['metadata']['dn_key']);


    if (!$delivery_note->id) {
        $html = '';
    }else {

        $tab     = 'delivery_note.fast_track_packing';
        $ar_file = 'ar_orders_tables.php';
        $tipo    = 'delivery_note.fast_track_packing';

        $default = $user->get_tab_defaults($tab);


        $table_views = array(
            'overview' => array(
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
            'parent'     => 'delivery_note',
            'parent_key' => $delivery_note->id

        );

        $smarty->assign(
            'table_metadata',
            json_encode(
                array(
                    'parent'     => 'delivery_note',
                    'parent_key' => $delivery_note->id
                )
            )

        );

        $smarty->assign('dn', $delivery_note);
        $smarty->assign('order', $state['_object']);

        $smarty->assign('store', $state['store']);
        $smarty->assign('parent', $state['store']);


        $warehouse = get_object('warehouse', $delivery_note->get('Delivery Note Warehouse Key'));
        $shippers = $warehouse->get_shippers('data', 'Active');

        $smarty->assign('shippers', $shippers);
        $smarty->assign('number_shippers', count($shippers));

        $table_buttons = array();


        $table_buttons[] = array(
            'icon'  => 'sign-out fa-flip-horizontal',
            'title' => _('Exit'),
            'id'    => 'exit_fast_track_packing',


        );

        $smarty->assign('table_buttons', $table_buttons);

        $smarty->assign('table_top_template', 'input_picking_sheet.tpl');


        $delivery_note = get_object('Delivery_Note', $state['metadata']['dn_key']);

        $smarty->assign('operations_view', (!empty($state['metadata']['type'])?$state['metadata']['type']:'data_entry_delivery_note'  )  );


        include('utils/get_table_html.php');
    }
}


