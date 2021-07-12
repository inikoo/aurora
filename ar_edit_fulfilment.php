<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 12 Jul 2021 18:11:06 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */
require 'vendor/autoload.php';

require_once 'common.php';
require_once 'utils/ar_common.php';

/** @var PDO $db */
/** @var \User $user */
/** @var array $editor */


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'msg'   => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}

$tipo = $_REQUEST['tipo'];


switch ($tipo) {
    case 'add_fulfilment_asset':
        $data = prepare_values(
            $_REQUEST, array(
                         'fulfilment_delivery_key' => array('type' => 'key'),
                         'asset_data'              => array('type' => 'json array'),
                     )
        );
        add_fulfilment_asset($data, $editor);
        break;

}

function add_fulfilment_asset($data, $editor) {

    $fulfilment_delivery = get_object('Fulfilment_Delivery', $data['fulfilment_delivery_key']);
    $fulfilment_delivery->editor=$editor;
    if ($fulfilment_delivery->id) {

        if ($fulfilment_delivery->get('State Index')==10 or $fulfilment_delivery->get('State Index')==40) {



            if($data['asset_data']['options']['type']=='add_one'){
                $fulfilment_asset=$fulfilment_delivery->create_fulfilment_asset($data['asset_data']['fields']);
                if($fulfilment_asset){



                    $response=[
                        'state'=>200,
                        'asset_key'=>$fulfilment_asset->id,
                        'metadata' => array(
                            'class_html' => [
                                'Fulfilment_Delivery_Number_Items'=>$fulfilment_delivery->get('Number Items')
                            ]
                        )
                    ];
                }else{
                    $response = array(
                        'state' => 400,
                        'msg'   => $fulfilment_delivery->msg
                    );
                }
            }elseif($data['asset_data']['options']['type']=='add_multiple'){
                $result=$fulfilment_delivery->create_multiple_fulfilment_asset($data['asset_data']['options']['number_assets'],$data['asset_data']['fields']);
                if($result['assets_added']>0){
                    $response=[
                        'state'=>200,
                        'added_assets'=>$result['assets_added'],
                        'metadata' => array(
                            'class_html' => [
                                'Fulfilment_Delivery_Number_Items'=>$fulfilment_delivery->get('Number Items')
                            ]
                        )
                    ];
                }else{
                    $response = array(
                        'state' => 400,
                        'msg'   => $fulfilment_delivery->msg
                    );
                }
            }



        }else{
            $response = array(
                'state' => 400,
                'msg'   => sprintf('Assets can not be added to deliveries with state: %s',$fulfilment_delivery->get('State'))
            );
        }
    } else {
        $response = array(
            'state' => 400,
            'msg'   => 'Delivery not found'
        );
    }
    echo json_encode($response);

}