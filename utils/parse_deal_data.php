<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Thu 11 April  2019 12:56:09 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 3.0
*/


function parse_deal_not_ordered_free_item($data, $deal_new_data, $store) {

    $product = get_object('Product', $data['fields_data']['Get Item Free Product']);
    if (!$product->id) {
        $response = array(
            'state' => 400,
            'resp'  => 'Product not found'
        );

        return array(
            false,
            $response
        );
    }

    if ($store->id != $product->get('Store Key')) {
        $response = array(
            'state' => 400,
            'resp'  => 'Product and store don not match'
        );
        return array(
            false,
            $response
        );
    }

    $qty = $data['fields_data']['Get Item Free Quantity'];


    if ($qty == '' or $qty == 0) {
        $response = array(
            'state' => 400,
            'resp'  => _("Free items quantity can't be zero")
        );
        return array(
            false,
            $response
        );
    }

    if (!is_numeric($qty) or $qty <= 0) {
        $response = array(
            'state' => 400,
            'resp'  => _("Invalid free items quantity")
        );
        return array(
            false,
            $response
        );
    }


    if ($qty == 1) {
        $deal_new_data['Deal Allowance Label'] = sprintf(_('Get one %s free'), $product->get('Code'));
    } else {
        $deal_new_data['Deal Allowance Label'] = sprintf(_('Get %s %s free'), $qty, $product->get('Code'));
    }


    $new_component_data = array(

        'Deal Component Allowance Label'        => $deal_new_data['Deal Allowance Label'],
        'Deal Component Allowance Type'         => 'Get Free No Ordered Product',
        'Deal Component Allowance Target'       => 'Product',
        'Deal Component Allowance Target Type'  => 'Items',
        'Deal Component Allowance Target Key'   => $product->id,
        'Deal Component Allowance Target Label' => $product->get('Code'),
        'Deal Component Allowance'              => json_encode(
            array(
                'object'=>'Product',
                'key'=>$product->id,
                'qty'=>$qty
            )

        )
    );

    return array(
        true,
        array(
            $deal_new_data,
            $new_component_data
        )
    );

}

function parse_deal_amount_off($data, $deal_new_data, $store) {


    $amount_off = $data['fields_data']['Amount Off'];


    if ($amount_off == '' or $amount_off == 0) {
        $response = array(
            'state' => 400,
            'resp'  => _("Amount off can't be zero")
        );
        return array(
            false,
            $response
        );
    }


    if (!is_numeric($amount_off) or $amount_off <= 0) {
        $response = array(
            'state' => 400,
            'resp'  => _("Invalid amount")
        );
        return array(
            false,
            $response
        );
    }

    if ($data['fields_data']['Trigger Extra Amount Net'] == '' or $data['fields_data']['Trigger Extra Amount Net'] == 0) {
        $response = array(
            'state' => 400,
            'resp'  => _("A minimum order value has to be set for amount off discount type")
        );
        return array(
            false,
            $response
        );
    }

    if (!is_numeric($data['fields_data']['Trigger Extra Amount Net']) or $data['fields_data']['Trigger Extra Amount Net'] < 0) {
        $response = array(
            'state' => 400,
            'resp'  => _("Invalid the minimum order value")
        );
        return array(
            false,
            $response
        );
    }

    if ($amount_off > $data['fields_data']['Trigger Extra Amount Net']) {
        $response = array(
            'state' => 400,
            'resp'  => _("Amount off must me less than the minimum order value")
        );
        return array(
            false,
            $response
        );
    }


    $deal_new_data['Deal Allowance Label'] = sprintf(_('%s off'), money($amount_off, $store->get('Store Currency Code')));


    $new_component_data = array(

        'Deal Component Allowance Label'        => $deal_new_data['Deal Allowance Label'],
        'Deal Component Allowance Type'         => 'Amount Off',
        'Deal Component Allowance Target'       => 'Order',
        'Deal Component Allowance Target Type'  => 'Items',
        'Deal Component Allowance Target Key'   => '',
        'Deal Component Allowance Target Label' => '',
        'Deal Component Allowance'              => $amount_off
    );

    return array(
        true,
        array(
            $deal_new_data,
            $new_component_data
        )
    );

}