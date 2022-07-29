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

$payment_account_key = $website->get_payment_account__key('Hokodo');


if (!$payment_account_key) {
    exit();
}

//Defaults

$company_type='registered-company';
if( $customer->get('hokodo_type')!=''){
    $company_type=$customer->get('hokodo_type');

}



$customer_set_up = false;
if ($customer->get('hokodo_user_id') and $customer->get('hokodo_org_id')) {
    $customer_set_up = true;
}

$smarty->assign('company_type',$company_type);
$smarty->assign('customer',$customer);


$hokodo_data=$customer->get('hokodo_array_data');
$smarty->assign('hokodo_data',$hokodo_data);

$saved_company_info='';
$saved_solo_info='';

if(isset($hokodo_data['name'] ) ){
    $saved_company_info=$hokodo_data['name'];
}
if(isset($hokodo_data['trading_name'] ) ){
    $saved_solo_info=$hokodo_data['sole-trader']['trading_name'];
}

$smarty->assign('saved_company_info',$saved_company_info);
$smarty->assign('saved_solo_info',$saved_solo_info);


$devel=false;
if (ENVIRONMENT == 'DEVEL') {
    $devel=true;
}
$smarty->assign('devel',$devel);

$smarty->assign('public_api_key',$website->get_public_api_key('Hokodo'));
$smarty->assign('order_id',$order->id);

$store=get_object('Store',$order->get('Order Store Key'));

$locale=substr($store->get('Store Locale'),0,2);
$smarty->assign('locale',$locale);


$smarty->display('theme_1/hokodo_sdk_checkout.EcomB2B.tpl');
