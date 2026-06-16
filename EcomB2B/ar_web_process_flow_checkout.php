<?php

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

use Checkout\CheckoutApiException;
use Checkout\CheckoutSdk;
use Checkout\Environment;


include_once 'payment_account_flow_checkout_common_functions.php';

//print "================";
//print_r($_REQUEST);
//exit;


include_once 'ar_web_common_logged_in.php';
/** @var Public_Customer $customer */

$smarty               = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

$account = get_object('Account', 1);

$website = get_object('Website', $_SESSION['website_key']);


$sql =
    "SELECT `Payment Account Store Payment Account Key` FROM `Payment Account Store Bridge` B left join `Payment Account Dimension` PAD on PAD.`Payment Account Key`=B.`Payment Account Store Payment Account Key`
WHERE `Payment Account Store Website Key`=? AND `Payment Account Store Status`='Active' AND `Payment Account Store Show in Cart`='Yes' and `Payment Account Block`='CheckoutFlow' ";
/** @var TYPE_NAME $db */
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $website->id
    ]
);
$payment_account_key_key = false;
while ($row = $stmt->fetch()) {
    $payment_account_key = $row['Payment Account Store Payment Account Key'];
}

if (!$payment_account_key) {
    exit();
}


$payment_account = get_object('Payment_Account', $payment_account_key);

$order = get_object('Order', $customer->get_order_in_process_key());


if (!$order->id) {
    echo '<div style="margin:100px auto;text-align: center">Order not found</div>';
    exit;
}

if (!empty($_REQUEST['id'])) {
    $payment_id   = $_REQUEST['id'];
    $payment_data = get_flow_payment($payment_account, $payment_id);

    if ($payment_data['status'] != 200) {
        $_SESSION['checkout_payment_error'] = 'Unknown error';
        if ($payment_data['status'] == '404') {
            $_SESSION['checkout_payment_error'] = 'Payment not found';
        }


        $redirect = "Location: checkout.sys?error=payment&t=1xx";

        header($redirect);
        exit;
    }
} else {
    $_SESSION['checkout_payment_error'] = 'Unknown error';

    $redirect = "Location: checkout.sys?error=payment&t=1xx";
    if ($website->get('Website Type') == 'EcomDS') {
        $redirect .= '&order_key='.$order->id;
    }


    header($redirect);
    exit;
}

if($payment_data['response']['approved'] == 1){
    print "Approved";
    exit;
}


print_r($payment_data);
exit;

$payment_data = get_checkout_flow_payment_data($order, $payment_data);

$customer = get_object('Customer', $order->get('Order Customer Key'));
$store    = get_object('Store', $order->get('Order Store Key'));

$payment = $payment_account->create_payment($payment_data);
$order->add_payment($payment);
$credits = floatval($customer->get('Customer Account Balance'));

if ($credits > 0) {
    $to_pay_credits = min($order->get('Order To Pay Amount'), $credits);
    list($customer, $order, $credit_payment_account, $credit_payment) = pay_credit($order, $to_pay_credits, $editor, $db, $account);
}


place_order($store, $order, $payment_account->id, $customer, $website, $editor, $smarty, $account, $db);

setcookie('au_pu_'.$order->id, $order->id, time() + 300, "/");
header('Location: thanks.sys?order_key='.$order->id.'&ts='.time());






//if ($res['state'] == 400) {
//    $_SESSION['checkout_payment_error'] = strip_tags($res['msg']);
//
//    $redirect="Location: checkout.sys?error=payment&t=1xx";
//    if ($website->get('Website Type') == 'EcomDS') {
//        $redirect.='&order_key='.$order->id;
//    }
//
//
//    header($redirect);
//
//} elseif ($res['state'] == 200) {
//    setcookie('au_pu_'.$order->id, $order->id, time() + 300, "/");
//    header('Location: thanks.sys?order_key='.$order->id.'&ts='.time());
//} else {
//    $_SESSION['checkout_payment_error'] = _('Unknown error please contact customer services');
//
//
//
//    $redirect="Location: checkout.sys?error=payment&t=2";
//    if ($website->get('Website Type') == 'EcomDS') {
//        $redirect+'&order_key='.$order->id;
//    }
//    header($redirect);
//
//
//}


