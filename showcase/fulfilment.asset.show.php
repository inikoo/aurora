<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 14 Jul 2021 03:34:54 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */


/**
 * @param $data
 * @param $smarty \Smarty
 *
 * @return string
 * @throws \SmartyException
 */
function get_fulfilment_asset_showcase($data, Smarty $smarty): string {


    /**
     * @var $asset \Fulfilment_Asset
     */
    $asset = $data['_object'];
    if (!$asset->id) {
        return "";
    }

    $customer  = get_object('Customer', $asset->get('Customer Key'));
    $store     = get_object('Store', $customer->get('Store Key'));
    $delivery  = get_object('Fulfilment_Delivery', $asset->get('Fulfilment Asset Fulfilment Delivery Key'));


    $smarty->assign('customer', $customer);
    $smarty->assign('asset', $asset);
    $smarty->assign('delivery', $delivery);
    $smarty->assign('store', $store);

    $smarty->assign(
        'object_data', json_encode(
                         array(
                             'object' => $data['object'],
                             'key'    => $data['key'],

                         )
                     )

    );



    $smarty->assign('labels_data', $asset->get_labels_data());


    return $smarty->fetch('showcase/fulfilment.asset.tpl');


}



