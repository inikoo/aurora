<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 19 Jul 2022 17:55:50 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

/** @var Order $order */

/** @var PDO $db */
include_once 'ar_web_common_logged_in.php';
include_once 'hokodo/api_call.php';
use Aurora\Models\Utils\TaxCategory;


/** @var Public_Customer $customer */


$website = get_object('Website', $_SESSION['website_key']);
$api_key = $website->get_api_key('Hokodo');

$account = get_object('Account', 1);


$api_key = $website->get_api_key('Hokodo');

$items = [];

$items_total = 0;
$items_tax   = 0;


$sql  = "select * from `Order Transaction Fact` OTF  left join `Product Dimension` P  on (OTF.`Product ID`=P.`Product Id`) where `Order Key`=? 
                                                                                                                    and `Order Quantity`>0
                                                                                                                    and `Order Transaction Amount`!=0 ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $order->id
    ]
);
while ($row = $stmt->fetch()) {
    $item_total  = round(100 * ($row['Order Transaction Amount'] + ($row['Order Transaction Amount'] * $row['Transaction Tax Rate'])));
    $item_tax    = round(100 * $row['Order Transaction Amount'] * $row['Transaction Tax Rate']);
    $items_total += $item_total;
    $items_tax   += $item_tax;

    $items[] = [
        "item_id"            => $row['Order Transaction Fact Key'],
        "type"               => "product",
        "description"        => $row['Product Code'].' '.$row['Product Name'],
        "quantity"           => $row['Order Quantity'],
        "unit_price"         => round($item_total / $row['Order Quantity']),
        "tax_rate"           => 100 * $row['Transaction Tax Rate'],
        "total_amount"       => $item_total,
        "tax_amount"         => $item_tax,
        "fulfilled_quantity" => 0,
        "fulfillment_info"   => null,
        "cancelled_quantity" => 0,
        "cancelled_info"     => null,
        "returned_quantity"  => 0,
        "returned_info"      => null
    ];
}

$sql  = "select * from `Order No Product Transaction Fact` OTF  where `Order Key`=? and (`Transaction Net Amount`+`Transaction Tax Amount`)>0
                                                                                                                   ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $order->id
    ]
);
while ($row = $stmt->fetch()) {
    $type = 'shipping';
    if ($row['Transaction Type'] !== 'Shipping') {
        $type = 'discount';
        if (($row['Transaction Net Amount'] + $row['Transaction Tax Amount']) > 0) {
            $type = 'fee';
        }
    }

    $tax_rate = 0;
    $sql      = "select `Tax Category Rate` from kbase.`Tax Category Dimension` where `Tax Category Key`=? ";
    $stmt2    = $db->prepare($sql);
    $stmt2->execute(
        [
            $row['Order No Product Transaction Tax Category Key']
        ]
    );
    while ($row2 = $stmt2->fetch()) {
        $tax_rate = $row2['Tax Category Rate'] * 100;
    }
    $item_total  = round(100 * ($row['Transaction Net Amount'] + $row['Transaction Tax Amount']));
    $item_tax    = round(100 * $row['Transaction Tax Amount']);
    $items_total += $item_total;
    $items_tax   += $item_tax;


    $items[] = [
        "item_id"            => 'np-'.$row['Order No Product Transaction Fact Key'],
        "type"               => $type,
        "description"        => $row['Transaction Type'],
        "quantity"           => 1,
        "unit_price"         => $item_total,
        "tax_rate"           => $tax_rate,
        "total_amount"       => $item_total,
        "tax_amount"         => $item_tax,
        "fulfilled_quantity" => 0,
        "fulfillment_info"   => null,
        "cancelled_quantity" => 0,
        "cancelled_info"     => null,
        "returned_quantity"  => 0,
        "returned_info"      => null
    ];
}


if ($order->get('Order Deal Amount Off') != '' and $order->get('Order Deal Amount Off') > 0) {


    $discount_net = -$order->get('Order Deal Amount Off');

    $tax_category = new TaxCategory($db);
    $tax_category->loadWithKey($order->data['Order Tax Category Key']);

    if ($tax_category->id) {
        $tax_rate = $tax_category->get('Tax Category Rate');
    } else {
        $tax_rate = 0;
    }


    $discount_tax = $discount_net * $tax_rate;
    $discount_total = $discount_net + $discount_tax;
    $sql = "SELECT  `Order Transaction Tax Category Key`  FROM `Order Transaction Fact` WHERE `Order Key`=?  AND `Order Transaction Type`='Order' GROUP BY  `Order Transaction Tax Category Key`";


    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $order->id
        )
    );

    $amount_off_processed = false;


    while ($row = $stmt->fetch()) {


        if ($order->data['Order Tax Category Key'] == $row['Order Transaction Tax Category Key']) {
            $amount_off_processed = true;
        }
    }



    if ($amount_off_processed) {
        $items[] = [
            "item_id"            => 'discount-'.$order->id,
            "type"               => 'discount',
            "description"        => 'Discount',
            "quantity"           => 1,
            "unit_price"         => round($discount_total * 100),
            "tax_rate"           => round($tax_rate* 100,4),
            "total_amount"       => round($discount_total * 100),
            "tax_amount"         => round($discount_tax * 100),
            "fulfilled_quantity" => 0,
            "fulfillment_info"   => null,
            "cancelled_quantity" => 0,
            "cancelled_info"     => null,
            "returned_quantity"  => 0,
            "returned_info"      => null
        ];
    }
}

$data = array(
    "unique_id"    => $order->get('Public ID'),
    'customer'     => [
        "type"             => "registered",
        "organisation"     => $customer->get('hokodo_org_id'),
        "user"             => $customer->get('hokodo_user_id'),
        "delivery_address" => [
            "name"          => $customer->get('Customer Delivery Address Recipient'),
            "address_line1" => $customer->get('Customer Delivery Address Line 1'),
            "address_line2" => $customer->get('Customer Delivery Address Line 2').'.',
            "city"          => $customer->get('Customer Delivery Address Locality'),
            "postcode"      => $customer->get('Customer Delivery Address Postal Code'),
            "country"       => $customer->get('Customer Delivery Address Country 2 Alpha Code'),
        ],
        "invoice_address"  => [
            "name"          => $customer->get('Customer Invoice Address Recipient'),
            "address_line1" => $customer->get('Customer Invoice Address Line 1'),
            "address_line2" => $customer->get('Customer Invoice Address Line 2').'.',
            "city"          => $customer->get('Customer Invoice Address Locality'),
            "postcode"      => $customer->get('Customer Invoice Address Postal Code'),
            "country"       => $customer->get('Customer Invoice Address Country 2 Alpha Code'),
        ],
    ],
    "status"       => "draft",
    "currency"     => $order->get('Order Currency'),
    "total_amount" => round($order->get('Order Total Amount') * 100),
    "tax_amount"   => round($order->get('Order Total Tax Amount') * 100),
    "order_date"   => date('Y-m-d'),
    'items'        => $items

);




//print round($order->get('Order Total Amount') * 100). ' '.$items_total."\n";
//exit;


// print $api_key.' || ';
//print_r($data);
//exit;



$raw_results = api_post_call('payment/orders', $data, $api_key);


 //print_r($raw_results);

if (!empty($raw_results['id'])) {
    // print_r($raw_results);


    $order_id = $raw_results['id'];


    $website_url = 'https://'.$website->get('Website URL');

    $notification_url = "$website_url/ar_web_hokodo_notification.php?order_id=".$order->id;

    if (ENVIRONMENT == 'DEVEL') {
        $website_url      = 'http://ds.ir:88';
        $notification_url = "https://b88c-202-187-92-228.ngrok.io/test_ar_web_hokodo_notification.php?order_id=".$order->id;
    }


    $offer_data = [
        'order' => $order_id,
        'urls'  => [
            "success"        => "$website_url/checkout.sys",
            "failure"        => "$website_url/checkout.sys",
            "cancel"         => "$website_url/checkout.sys",
            "notification"   => $notification_url,
            "merchant_terms" => "$website_url/return_policy",
        ]
    ];

    // print_r($offer_data);

    $raw_results = api_post_call('payment/offers', $offer_data, $api_key);

    $status = 'rejected';
    if (isset($raw_results['id'])) {
        foreach ($raw_results['offered_payment_plans'] as $plan_data) {
            if ($plan_data['status'] == 'offered') {
                $status = 'accepted';
            }
        }
    }


    echo json_encode(
        [
            'status'   => $status,
            'response' => $raw_results,
        ]
    );
    exit;
}

return [
    'status'   => 'error',
    'msg'      => 'Sorry, we can not offer you this service',
    'raw_data' => json_encode($raw_results)
];


echo json_encode($res);
exit;


