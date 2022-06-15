<?php

/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 15 Jun 2022 11:56:00 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */
include_once 'ar_web_common_logged_out.php';


if (!defined('AU_LOQATE_KEY') or empty($_REQUEST['country'])) {
    echo json_encode(
        [
            'status' => 400
        ]
    );
    return;
}
$original_address = '';
$address          = '';
$city             = '';
$postcode         = '';
$country          = $_REQUEST['country'];
$org              = '';

if (!empty($_REQUEST['address'])) {
    $original_address = $_REQUEST['address'];
    $address          = $_REQUEST['address'];
}
if (!empty($_REQUEST['city'])) {
    $address = preg_replace('/'.$_REQUEST['city'].'/', '', $address);
    $city    = $_REQUEST['city'];
}
if (!empty($_REQUEST['postcode'])) {
    $address  = preg_replace('/'.$_REQUEST['postcode'].'/', '', $address);
    $postcode = $_REQUEST['postcode'];
}

if (!empty($_REQUEST['org'])) {
    $org = $_REQUEST['org'];
}

$address = preg_replace('/,\s*,/', ',', $address);
$address = preg_replace('/,\s*$/', '', $address);

if ($address == '') {
    echo json_encode(
        [
            'status' => 400
        ]
    );
    return;
}


$ch = curl_init();

$base_url = 'https://api.addressy.com/Cleansing/International/Batch/v1.00/json4.ws';


curl_setopt($ch, CURLOPT_URL, $base_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);


$data = [
    "Key"       => AU_LOQATE_KEY,
    "Addresses" => [
        [
            'Address'      => $address,
            'Locality'     => $city,
            'PostalCode'   => $postcode,
            'Country'      => $country,
            'Organization' => $org
        ]
    ]
];


curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$headers   = array();
$headers[] = 'Content-Type: application/json';

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);


$res = curl_exec($ch);


$res = json_decode(curl_exec($ch), true);

if ($res and is_array($res)) {
    $res = array_shift($res);
    if (isset($res['Matches']) and is_array($res['Matches'])) {
        $raw_data = array_shift($res['Matches']);


        //print_r($raw_data);

        $dependentLocality = '';
        if (!empty($raw_data['DependentLocality'])) {
            $dependentLocality = $raw_data['DependentLocality'];
        }


        $administrativeArea = '';
        if (!empty($raw_data['AdministrativeArea'])) {
            $administrativeArea = $raw_data['AdministrativeArea'];
        }

        $address1='';
        if (!empty($raw_data['DeliveryAddress1'])) {
            $address1=$raw_data['DeliveryAddress1'];
        }


        $address2='';
        if (!empty($raw_data['DeliveryAddress2'])) {
            $address2 = $raw_data['DeliveryAddress2'];
            if (!empty($raw_data['DeliveryAddress3'])) {
                if ($address2 != '') {
                    $address2 .= ', ';
                }
                $address2 .= $raw_data['DeliveryAddress3'];

                if ($dependentLocality == $raw_data['DeliveryAddress3']) {
                    $dependentLocality = 'x';
                }
            } else {
                if ($dependentLocality == $raw_data['DeliveryAddress2']) {
                    $dependentLocality = 'y';
                }
            }
        }
        $data = [
            'addressLine1'       => $address1,
            'addressLine2'       => $address2,
            'dependentLocality'  => $dependentLocality,
            'administrativeArea' => $administrativeArea

        ];
        if (!empty($raw_data['Locality'])) {
            $data['locality'] = $raw_data['Locality'];
        }

        echo json_encode(
            [
                'status'           => 200,
                'address_fields'   => $data,
                'original_address' => $original_address,
                'address'          => $address,

            ]
        );
    }
}


