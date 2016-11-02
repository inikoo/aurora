<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 13:57:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_supplier_order_showcase($data, $smarty, $user, $db) {

    //$data['_object']->update_totals();

    if (!$data['_object']->id) {
        return "";
    }

    if ($data['_object']->deleted) {

        return '';

    } else {


        $smarty->assign('order', $data['_object']);

        $_parent = get_object(
            $data['_object']->get('Purchase Order Parent'), $data['_object']->get('Purchase Order Parent Key')
        );

        $smarty->assign('parent', $_parent);


        $smarty->assign(
            'object_data', base64_encode(
                json_encode(
                    array(
                        'object'                        => $data['object'],
                        'key'                           => $data['key'],
                        'order_parent'                  => $data['_object']->get(
                            'Purchase Order Parent'
                        ),
                        'order_parent_key'              => $data['_object']->get(
                            'Purchase Order Parent Key'
                        ),
                        'tab'                           => $data['tab'],
                        'purchase_order_number'         => $data['_object']->get(
                            'Purchase Order Public ID'
                        ),
                        'skip_inputting'                => $_parent->get(
                            'Parent Skip Inputting'
                        ),
                        'skip_mark_as_dispatched'       => $_parent->get(
                            'Parent Skip Mark as Dispatched'
                        ),
                        'skip_mark_as_received'         => $_parent->get(
                            'Parent Skip Mark as Received'
                        ),
                        'skip_checking'                 => $_parent->get(
                            'Parent Skip Checking'
                        ),
                        'automatic_placement_locations' => $_parent->get(
                            'Parent Automatic Placement Location'
                        )


                    )
                )
            )
        );


        if ($data['_object']->get('Purchase Order Submitted Date') != '') {
            $mindate_send_order = date(
                'U', strtotime(
                    $data['_object']->get('Purchase Order Submitted Date').' +0:00'
                )
            );
        } else {
            $mindate_send_order = date(
                'U', strtotime(
                    $data['_object']->get('Purchase Order Created Date').' +0:00'
                )
            );


        }
        $smarty->assign('mindate_send_order', 1000 * $mindate_send_order);

        if ($user->get('User Type') == 'Staff') {
            return $smarty->fetch('showcase/supplier.order.tpl');
        } elseif ($user->get('User Type') == 'Agent') {
            return $smarty->fetch('showcase/client_order.tpl');
        }
    }

}


?>
