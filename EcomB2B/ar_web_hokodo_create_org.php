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

$unique_id  = strtolower($account->get('Account Code')).'-'.$customer->id;
$company_id = '';
$company_name='';
if (!empty($_REQUEST['company_id'])) {
    $company_id = trim($_REQUEST['company_id']);
}
if (!empty($_REQUEST['company_name'])) {
    $company_name = trim($_REQUEST['company_name']);
}

if ($company_id == '') {
    echo json_encode(
        [
            'status' => 'error'
        ]
    );
    exit;
}


$email = $customer->get('Customer Main Plain Email');
//if (ENVIRONMENT == 'DEVEL') {
//    $email='rulovicoaa@gmail.com';
//}



$raw_results = api_post_call('organisations', array(
    "unique_id"  => $unique_id,
    "company"    => $company_id,
    'registered' => date('c', strtotime($customer->get('Customer First Contacted Date')))
),                           $api_key);


$org_id = $raw_results['id'];

$data    = array(
    "name"       => $customer->get('Customer Main Contact Name'),
    "email"      => trim($email),
    "phone"      => trim($customer->get_telephone()),
    'registered' => date('c', strtotime($customer->get('Customer First Contacted Date'))),
    'organisations' => []
);
$user_id = false;

$raw_results = api_post_call('users', $data, $api_key);

$user_id=$raw_results['id'];
$data        = [
    'id'   => $org_id,
    'role' => 'member'
];
$raw_results = api_post_call('users/'.$user_id.'/organisations', $data, $api_key);

/*
if (empty($raw_results['id'])) {

    if (!empty($raw_results['organisations'])) {
        $data['organisations'] = [];


        $raw_results = api_post_call('users', $data, $api_key);

        if (!empty($raw_results['id'])) {
            $user_id     = $raw_results['id'];
            $data        = [
                'id'   => $org_id,
                'role' => 'member'
            ];
            $raw_results = api_post_call('users/'.$user_id.'/organisations', $data, $api_key);

        }
    }
} else {
    $user_id = $raw_results['id'];
}
*/

if ($user_id) {


    $customer->fast_update(
        [
           'hokodo_co_id'=> $company_id,
           'hokodo_org_id'=> $org_id,
           'hokodo_user_id'=> $user_id,
           'hokodo_data'=>json_encode(['name'=>$company_name]),
           'hokodo_type'=>'registered-company'

        ]
    );



    $res = get_plans($db, $order, $customer, $website);

    echo json_encode($res);
    exit;


}

echo json_encode(
    [
        'status' => 'error'
    ]
);





