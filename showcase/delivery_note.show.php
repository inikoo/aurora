<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 16:46:50 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_delivery_note_showcase($data, $smarty) {



    if (!$data['_object']->id) {
        return "";
    }

    $smarty->assign('delivery_note', $data['_object']);

    $delivery_note = $data['_object'];


    $delivery_note->update_totals();

    //$delivery_note->get_label();


    $order = get_object('Order', $delivery_note->get('Delivery Note Order Key'));


    $store = get_object('Store', $delivery_note->get('Delivery Note Store Key'));


    $parcels     = $delivery_note->get('Parcels');
    $weight      = $delivery_note->data['Delivery Note Weight'];
    $consignment = $delivery_note->data['Delivery Note Shipper Consignment'];


    $smarty->assign('parcels', $parcels);
    $smarty->assign('weight', ($weight ? $delivery_note->get('Weight') : ''));
    $smarty->assign('consignment', ($consignment ? $delivery_note->get('Consignment') : ''));


    $warehouse = get_object('Warehouse', $delivery_note->get('Delivery Note Warehouse Key'));

    $shippers = $warehouse->get_shippers('data', 'Active');

    $smarty->assign('shippers', $shippers);
    $smarty->assign('number_shippers', count($shippers));


    $smarty->assign(
        'object_data', json_encode(
                         array(
                             'object' => $data['object'],
                             'key'    => $data['key'],

                             'tab' => $data['tab']
                         )
                     )

    );


    $smarty->assign('order', $order);
    $smarty->assign('store', $store);

    if ($delivery_note->get('Delivery Note Type') == 'Order') {
        return $smarty->fetch('showcase/delivery_note.tpl');

    } else {
        return $smarty->fetch('showcase/replacement.tpl');

    }


}

