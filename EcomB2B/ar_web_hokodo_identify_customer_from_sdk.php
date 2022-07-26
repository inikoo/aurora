<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 19 Jul 2022 12:55:33 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */


/** @var PDO $db */
/** @var Public_Customer $customer */

include_once 'ar_web_common_logged_in.php';
include_once 'hokodo/api_call.php';



if(!empty($_REQUEST['data'])){
    $company_data=$_REQUEST['data'];
}else{
    echo json_encode(
        [
            'status'=>'error'
        ]
    );
    exit;
}

$website = get_object('Website', $_SESSION['website_key']);
$api_key = $website->get_api_key('Hokodo');


$account = get_object('Account', 1);

$unique_id  = strtolower($account->get('Account Code')).'-'.$customer->id;
$company_id = trim($company_data['id']);


$email = $customer->get('Customer Main Plain Email');
if (ENVIRONMENT == 'DEVEL') {
    $email='rulovicoxxcaca2@gmail.com';
}

if($company_data['type']=='registered-company'){

    $company_name='';

    if (!empty($company_data['name'])) {
        $company_name = trim($company_data['name']);
    }





    $raw_results = api_post_call('organisations', array(
        "unique_id"  => $unique_id,
        "company"    => $company_id,
        'registered' => date('c', strtotime($customer->get('Customer First Contacted Date')))
    ),                           $api_key);
    $raw_results0=$raw_results;


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
    $raw_results1=$raw_results;
    $user_id=$raw_results['id'];
    $data        = [
        'id'   => $org_id,
        'role' => 'member'
    ];
    $raw_results = api_post_call('users/'.$user_id.'/organisations', $data, $api_key);

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
    }


}else{




    $customer->fast_update(
        [
            'hokodo_sole_id' => $company_data['id'],
        ]
    );


    // Create organization

    $raw_results = api_post_call('organisations', array(
        "unique_id"  => $unique_id,
        "company"    => $company_data['id'],
        'registered' => date('c', strtotime($customer->get('Customer First Contacted Date')))
    ),                           $api_key);


    $org_id = $raw_results['id'];


    //Create user


    $data        = array(
        "name"  => $customer->get('Customer Main Contact Name'),
        "email" => trim($email),
        "phone" => trim($customer->get_telephone()),

        'registered' => date('c', strtotime($customer->get('Customer First Contacted Date'))),

        'organisations' => []
    );
    $raw_results = api_post_call('users', $data, $api_key);


    if (!empty($raw_results['id'])) {
        $user_id = $raw_results['id'];


        $_data = [
            'id'   => $org_id,
            'role' => 'member'
        ];

        $raw_results = api_post_call('users/'.$user_id.'/organisations', $data, $api_key);


        // add user to soletrader



        $raw_results = api_post_call('soletraders/'.$company_data['id'],
            [
                'owner'=>$user_id
            ],

            $api_key,'PATCH');





        $customer->fast_update(
            [
                'hokodo_org_id'  => $org_id,
                'hokodo_user_id' => $user_id,
                'hokodo_co_id'   => $company_data['id'],
                'hokodo_type'    => 'sole-trader',
                'hokodo_data'    => json_encode(['sole-trader' => $_data])

            ]
        );
    }

}