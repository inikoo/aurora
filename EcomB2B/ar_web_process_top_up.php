<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Thu, 09 Dec 2021 21:03:04 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2021, Inikoo
 *  Version 3.0
 */

use Checkout\CheckoutApiException;
use Checkout\CheckoutSdk;
use Checkout\Environment;

include_once 'class.DBW_Table.php';

include_once 'class.Public_Top_Up.php';
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


if (empty($_REQUEST['top_up_key']) ) {
    echo '<div style="margin:100px auto;text-align: center">Top up key not provided (a)</div>';
    exit;
}
$top_up = new Public_Top_Up('id', $_REQUEST['top_up_key']);



$to_pay = $top_up->get('Top Up Amount');


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

$store = get_object('Store', $top_up->get('Top Up Store Key'));

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

$res      = process_payment_top_up_response($response, $top_up, $website, $payment_account, $customer, $editor, $account, $store, $db);


//exit;
if ($res['state'] == 400) {
    $_SESSION['top_up_payment_error'] = strip_tags($res['msg']);

    $redirect="Location: top_up.sys?error=payment&t=1xx";



    header($redirect);

} elseif ($res['state'] == 200) {




    header('Location: balance.sys');
} else {
    $_SESSION['top_up_payment_error'] = _('Unknown error please contact customer services');

    $redirect="Location: top_up.sys?error=payment&t=2";

    header($redirect);


}


