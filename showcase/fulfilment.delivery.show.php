<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 08 Jul 2021 17:53:38 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021., Inikoo
 *  Version 3.0
 */


/**
 * @param $data
 * @param $smarty \Smarty
 *
 * @return string
 * @throws \SmartyException
 */
function get_fulfilment_delivery_showcase($data, Smarty $smarty): string {


    /**
     * @var $delivery \Fulfilment_Delivery
     */
    $delivery = $data['_object'];
    if (!$delivery->id) {
        return "";
    }

    $customer = get_object('Customer', $delivery->get('Customer Key'));
    $store = get_object('Store', $customer->get('Store Key'));

    $smarty->assign('customer', $customer);
    $smarty->assign('delivery', $delivery);
    $smarty->assign('store', $delivery);

    $smarty->assign(
        'object_data', json_encode(
                         array(
                             'object' => $data['object'],
                             'key'    => $data['key'],

                         )
                     )

    );

    return $smarty->fetch('showcase/fulfilment.delivery.tpl');


}



