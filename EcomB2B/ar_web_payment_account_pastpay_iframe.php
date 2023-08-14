<?php

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
    if (empty($_REQUEST['order_key']) or !is_numeric($_REQUEST['order_key']) or $_REQUEST['order_key'] <= 0) {
        $response = array(
            'state' => 400,
            'html'  => '<div style="margin:100px auto;text-align: center">Client order key not provided (c)</div>'


        );
        echo json_encode($response);
        exit;
    }


    $order = get_object('Order', $_REQUEST['order_key']);

    if (!$order->id or $order->get('Order Customer Key') != $customer->id) {
        $response = array(
            'state' => 200,
            'html'  => '<div style="margin:100px auto;text-align: center">Incorrect order id</div>'

        );
        echo json_encode($response);
        exit;
    }

    if ($order->get('Order State') != 'InBasket') {
        $response = array(
            'state' => 200,
            'html'  => '<div style="margin:100px auto;text-align: center">Order not in basket</div>'

        );
        echo json_encode($response);
        exit;
    }
} else {
    $order = get_object('Order', $customer->get_order_in_process_key());
}

$to_pay = $order->get('Order To Pay Amount');

$payment_account_key = $website->get_payment_account__key('Pastpay');


if (!$payment_account_key) {
    exit();
}


//Notes: what if order partially paid by credit
// I am using round so  7.509
//Round up in Czech and other currencies

$payment_account=get_object('Payment_Account',$payment_account_key);

$settings=json_decode($payment_account->get('Payment Account Settings'),true);



$currency=$order->get('Order Currency');
$plans=$settings[$currency];


//$gross_amount=$order->get('Order Total Amount');
$gross_amount=$to_pay;
$rounder=2;

foreach($plans as $key=>$plan){

    $charge_amount=round($gross_amount* $plan['charge'],$rounder);

    $new_to_pay=$charge_amount+$to_pay;

    $plans[$key]['charge_formatted']=percentage($plan['charge'],1,2);
    $plans[$key]['term_formatted']=$plan['term'].' '.ngettext('day','days',$plan['term']);

    $plans[$key]['charge_amount']=$charge_amount;
    $plans[$key]['charge_amount_formatted']=money($charge_amount,$currency);
    $plans[$key]['to_pay']=money($new_to_pay,$currency);

}


//Defaults

$smarty->assign('order',$order);
$smarty->assign('customer',$customer);
$smarty->assign('plans',$plans);
$smarty->assign('labels', $website->get('Localised Labels'));
$smarty->display('theme_1/pastpay_checkout.EcomB2B.tpl');
