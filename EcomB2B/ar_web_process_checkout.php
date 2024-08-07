<?php

use Checkout\CheckoutApiException;
use Checkout\CheckoutSdk;
use Checkout\Environment;


include_once 'payment_account_checkout_common_functions.php';


if (!empty($_REQUEST['cko-session-id'])) {
    $payment_id = $_REQUEST['cko-session-id'];
} else {
    exit;
}


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
if ($website->get('Website Type') == 'EcomDS') {
    if (empty($_REQUEST['client_order_key']) or !is_numeric($_REQUEST['client_order_key']) or $_REQUEST['client_order_key'] <= 0) {
        echo '<div style="margin:100px auto;text-align: center">Client order key not provided (a)</div>';
        exit;
    }


    $order = get_object('Order', $_REQUEST['client_order_key']);

    if (!$order->id or $order->get('Order Customer Key') != $customer->id) {
        echo '<div style="margin:100px auto;text-align: center">Incorrect order id</div>';
        exit;
    }

    if ($order->get('Order State') != 'InBasket') {
        echo '<div style="margin:100px auto;text-align: center">Order not in basket</div>';
        exit;
    }
} else {
    $order = get_object('Order', $customer->get_order_in_process_key());
}

if (!$order->id) {
    echo '<div style="margin:100px auto;text-align: center">Order not found</div>';
    exit;
}


$to_pay = $order->get('Order To Pay Amount');


$sql =
    "SELECT `Payment Account Store Payment Account Key` FROM `Payment Account Store Bridge` B left join `Payment Account Dimension` PAD on PAD.`Payment Account Key`=B.`Payment Account Store Payment Account Key`
WHERE `Payment Account Store Website Key`=? AND `Payment Account Store Status`='Active' AND `Payment Account Store Show in Cart`='Yes' and `Payment Account Block`='Checkout' ";
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
/** @var TYPE_NAME $editor */
$payment_account->editor = $editor;




$secretKey=$payment_account->get('Payment Account Password');


$store = get_object('Store', $order->get('Order Store Key'));

$CheckoutAPI = CheckoutSdk::builder()->staticKeys()

    ->secretKey($secretKey)
    ->environment(ENVIRONMENT == 'DEVEL' ?Environment::sandbox(): Environment::production())
    ->build();


try {
    $response = $CheckoutAPI->getPaymentsClient()->getPaymentDetails($payment_id);

} catch (CheckoutApiException $e) {
    print_r($e);
    exit;
}

$res      = process_payment_response($response, $order, $website, $payment_account, $customer, $editor, $smarty, $account, $store, $db);


//exit;
if ($res['state'] == 400) {
    $_SESSION['checkout_payment_error'] = strip_tags($res['msg']);

    $redirect="Location: checkout.sys?error=payment&t=1xx";
    if ($website->get('Website Type') == 'EcomDS') {
        $redirect.='&order_key='.$order->id;
    }


    header($redirect);

} elseif ($res['state'] == 200) {
    setcookie('au_pu_'.$order->id, $order->id, time() + 300, "/");
    header('Location: thanks.sys?order_key='.$order->id.'&ts='.time());
} else {
    $_SESSION['checkout_payment_error'] = _('Unknown error please contact customer services');



    $redirect="Location: checkout.sys?error=payment&t=2";
    if ($website->get('Website Type') == 'EcomDS') {
        $redirect+'&order_key='.$order->id;
    }
    header($redirect);


}


