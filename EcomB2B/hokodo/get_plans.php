<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Wed, 27 Apr 2022 09:11:50 Central European Summer Time, Cala Mijas Spain
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

include_once 'api_call.php';


function get_plans($db, $order, $customer, $website)
{
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
        $item_total  = floor(100 * ($row['Order Transaction Amount'] + ($row['Order Transaction Amount'] * $row['Transaction Tax Rate'])));
        $item_tax    = floor(100 * $row['Order Transaction Amount'] * $row['Transaction Tax Rate']);
        $items_total += $item_total;
        $items_tax   += $item_tax;

        $items[] = [
            "item_id"            => $row['Order Transaction Fact Key'],
            "type"               => "product",
            "description"        => $row['Product Code'].' '.$row['Product Name'],
            "quantity"           => $row['Order Quantity'],
            "unit_price"         => floor($item_total / $row['Order Quantity']),
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
        $item_total  = floor(100 * ($row['Transaction Net Amount'] + $row['Transaction Tax Amount']));
        $item_tax    = floor(100 * $row['Transaction Tax Amount']);
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
        "total_amount" => floor($order->get('Order Total Amount') * 100),
        "tax_amount"   => floor($order->get('Order Total Tax Amount') * 100),
        "order_date"   => date('Y-m-d'),
        'items'        => $items

    );

    // print $api_key.' || ';
    //print_r($data);
    //exit;


    $raw_results = api_post_call('payment/orders', $data, $api_key);
    // print_r($raw_results);

    if (!empty($raw_results['id'])) {
        // print_r($raw_results);


        $order_id = $raw_results['id'];


        $website_url = 'https://'.$website->get('Website URL');

        if (ENVIRONMENT == 'DEVEL') {
            $website_url = 'http://ds.ir:88';
        }


        $offer_data = [
            'order' => $order_id,
            'urls'  => [
                "success"        => "$website_url/ar_web_hokodo_success.php?order_id=".$order->id,
                "failure"        => "$website_url/checkout.sys",
                "cancel"         => "$website_url/checkout.sys",
                "notification"   => "$website_url/ar_web_hokodo_notification.php?order_id=".$order->id,
                // "notification"   => "https://8a88-212-139-223-82.ngrok.io/test_ar_web_hokodo_notification.php?order_id=".$order->id,
                "merchant_terms" => "$website_url/return_policy",
            ]
        ];

        // print_r($offer_data);

        $raw_results = api_post_call('payment/offers', $offer_data, $api_key);


        if (isset($raw_results['id'])) {
            //print_r($raw_results['offered_payment_plans']);


            $plans = '';

            foreach ($raw_results['offered_payment_plans'] as $plan_data) {
                if ($plan_data['status'] == 'offered') {
                    switch ($plan_data['name']) {
                        case '30d':
                            $plan_name = sprintf(_('%s days credit'), 30);
                            break;
                        case '60d':
                            $plan_name = sprintf(_('%s days credit'), 60);
                            break;
                        case '90d':
                            $plan_name = sprintf(_('%s days credit'), 90);
                            break;
                        default:
                            $plan_name = $plan_data['name'];
                    }


                    $details = 'Buy now. Pay Later.';
                    $details .= ' <b>'.'No interest. No fees.'.'</b>';

                    $plans .= ' <label class="rounded-tl-md rounded-tr-md relative border p-4 flex flex-col cursor-pointer  focus:outline-none">
                        <div class="flex items-center text-sm">
                            <input  type="radio" name="paying-plan"  data-id="'.$plan_data['id'].'"  value="'.$plan_data['payment_url'].'" class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500" aria-labelledby="paying-plans-0-label" aria-describedby="paying-plans-0-description-0 paying-plans-0-description-1">
                            <span  class="ml-3 font-medium">'.$plan_name.'</span>
                        </div>

                        <p  class="ml-6 pl-1 text-sm ">'.$details.'</p>
                    </label>';
                }
            }


            if ($plans == '') {
                return [
                    'status' => 'ok',
                    'plans'  => '<span style="color:tomato">Sorry, we can not offer you this payment method</span',
                ];
            }

            return [
                'status'   => 'ok',
                'plans'    => $plans,
                'order_id' => $order_id

            ];
        }
    }

    return [
        'status'   => 'error',
        'msg'      => 'Sorry, we can not offer you this service',
        'raw_data' => json_encode($raw_results)
    ];
}