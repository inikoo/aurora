<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 28 Apr 2022 15:46:42 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */


require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';

require '../vendor/autoload.php';
require_once 'utils/sentry.php';
require_once 'utils/object_functions.php';
require_once 'utils/general_functions.php';
require_once 'common_web_paying_functions.php';
require_once 'utils/placed_order_functions.php';
require_once 'utils/natural_language.php';
//require_once 'hokodo/api_call.php';


$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
$account = get_object('Account', 1);


$order   = get_object('Order', $_REQUEST['order_id']);
$store   = get_object('Store', $order->get('Order Store Key'));
$website = get_object('Website', $store->get('Store Website Key'));
$api_key = $website->get_api_key('Hokodo');



$data = getPost();


$sql="insert into atest2 (date,data) values (?,?) ";
$db->prepare($sql)->execute(
    [
        gmdate('Y-m-d H:i:s'),
        json_encode($data)
    ]
);




if (!empty($data['data']['order']['deferred_payment'])) {
    $deferred_payment = $data['data']['order']['deferred_payment'];



    $ref    = $deferred_payment['id'];
    $status = $deferred_payment['status'];


    $to_update=[
        'Payment Metadata'=>json_encode($data)
    ];


    if($status=='pending_review' or $status=='customer_action_required'){
        $status='Approving';
        $to_update['Payment Transaction Status']='Approving';
        $to_update['Payment Transaction Status Info']='Pending review';
        $to_update['Payment Transaction ID']=$ref;

    }elseif($status=='accepted'){

        $to_update['Payment Transaction Status']='Completed';
        $to_update['Payment Transaction Status Info']='Approved';
        $to_update['Payment Completed Date']=gmdate('Y-m-d H:i:s');
        $to_update['Payment Last Completed Date']=gmdate('Y-m-d H:i:s');
        $to_update['Payment Transaction ID']=$ref;


    }elseif($status=='rejected'){

        $to_update['Payment Transaction Status']='Declined';
        $to_update['Payment Transaction Status Info']='Rejected (fraud)';



    }elseif($status=='fulfilled'  or $status=='part_fulfilled'){

        $to_update['Payment Transaction Status Info']='Fulfilled';

        if(isset($data['data']['order']['total_amount'])){
            $to_update['Payment Transaction Amount']=$data['data']['order']['total_amount']/100;

        }



    }





    $payment = get_object('Payment', $order->data['pending_hokodo_payment_id']);

    $payment->fast_update(
        $to_update
    );

    $payment->update_payment_parents();

}




function getPost()
{
    if (!empty($_POST)) {
        // when using application/x-www-form-urlencoded or multipart/form-data as the HTTP Content-Type in the request
        // NOTE: if this is the case and $_POST is empty, check the variables_order in php.ini! - it must contain the letter P
        return $_POST;
    }

    // when using application/json as the HTTP Content-Type in the request
    $post = json_decode(file_get_contents('php://input'), true);
    if (json_last_error() == JSON_ERROR_NONE) {
        return $post;
    }

    return [];
}