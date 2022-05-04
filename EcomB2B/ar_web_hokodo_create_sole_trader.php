<?php
/** @var Order $order */

/** @var PDO $db */
include_once 'ar_web_common_logged_in.php';
include_once 'hokodo/api_call.php';
include_once 'hokodo/get_plans.php';

/** @var Public_Customer $customer */


$website = get_object('Website', $_SESSION['website_key']);
$api_key = $website->get_api_key('Hokodo');

$account = get_object('Account', 1);

$unique_id  = strtolower($account->get('Account Code')).'-'.$customer->id.date('U');
$company_id = '';


$email = $customer->get('Customer Main Plain Email');

if (ENVIRONMENT == 'DEVEL') {
  //  $email = 'rulovico@gmail.com';
}


if ($customer->data['hokodo_sole_id']) {
    $data        = [
        'trading_name'                => $_POST['trading_name'],
        "trading_address"             => $_POST['trading_address'],
        "trading_address_city"        => $_POST['trading_address_city'],
        "trading_address_postcode"    => $_POST['trading_address_postcode'],
        'proprietor_name'             => $_POST['proprietor_name'],
        "proprietor_address_line1"    => $_POST['proprietor_address'],
        "proprietor_address_line2"    => $_POST['proprietor_address2'].'.',
        "proprietor_address_city"     => $_POST['proprietor_address_city'],
        "proprietor_address_postcode" => $_POST['proprietor_address_postcode'],
        'date_of_birth'               => $_POST['birth_date'],

    ];
    $raw_results = api_post_call('soletraders/'.$customer->data['hokodo_sole_id'], $data, $api_key, 'PATCH');

   // print_r($raw_results);
   // exit;

    $customer->fast_update(
        [

            'hokodo_data' => json_encode(['sole-trader' => $data])

        ]
    );


    $res = get_plans($db, $order, $customer, $website);

    echo json_encode($res);
    exit;
} else {
    $data        = array(
        "name"       => $customer->get('Customer Main Contact Name'),
        "email"      => trim($email),
        "phone"      => trim($customer->get_telephone()),

        'registered' => date('c', strtotime($customer->get('Customer First Contacted Date'))),

        'organisations' => []
    );
    $raw_results = api_post_call('users', $data, $api_key);

    if (!empty($raw_results['id'])) {
        $user_id = $raw_results['id'];

        $data = [
            'owner'                       => $user_id,
            'trading_name'                => $_POST['trading_name'],
            "trading_address"             => $_POST['trading_address'],
            "trading_address_city"        => $_POST['trading_address_city'],
            "trading_address_postcode"    => $_POST['trading_address_postcode'],
            "trading_address_country"     => $customer->get('Customer Invoice Address Country 2 Alpha Code'),
            'proprietor_name'             => $_POST['proprietor_name'],
            "proprietor_address_line1"    => $_POST['proprietor_address'],
            "proprietor_address_line2"    => $_POST['proprietor_address2'].'.',
            "proprietor_address_city"     => $_POST['proprietor_address_city'],
            "proprietor_address_postcode" => $_POST['proprietor_address_postcode'],
            "proprietor_address_country"  => $customer->get('Customer Invoice Address Country 2 Alpha Code'),
            'unique_id'                   => $unique_id,
            'date_of_birth'               => $_POST['birth_date'],

        ];

        $raw_results = api_post_call('soletraders', $data, $api_key);

        if (!empty($raw_results['id'])) {
            $co_id = $raw_results['id'];

            $customer->fast_update(
                [
                    'hokodo_sole_id' => $co_id
                ]
            );


            $raw_results = api_post_call('organisations', array(
                "unique_id"  => $unique_id,
                "company"    => $co_id,
                'registered' => date('c', strtotime($customer->get('Customer First Contacted Date')))
            ),                           $api_key);


            $org_id = $raw_results['id'];


            $data        = [
                'id'   => $org_id,
                'role' => 'member'
            ];
            $raw_results = api_post_call('users/'.$user_id.'/organisations', $data, $api_key);


            $customer->fast_update(
                [
                    'hokodo_org_id'  => $org_id,
                    'hokodo_user_id' => $user_id,
                    'hokodo_co_id'   => $co_id,
                    'hokodo_type'    => 'sole-trader',
                    'hokodo_data'    => json_encode(['sole-trader' => $data])

                ]
            );


            $res = get_plans($db, $order, $customer, $website);

            echo json_encode($res);
            exit;
        }
    }
}

