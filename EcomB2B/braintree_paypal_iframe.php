<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:8 September 2015 15:04:59 GMT+8, Kuala Lumpur , Malaysia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/

include_once 'keyring/key.php';
include_once 'utils/aes.php';

date_default_timezone_set('Europe/London');

require_once 'external_libs/Smarty/Smarty.class.php';
$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';


$key  = md5('83edh3847203942,'.CKEY);
$data = json_decode(AESDecryptCtr(base64_decode($_REQUEST['data']), $key, 256), true);


$braintree_clientToken = '';

require_once 'external_libs/braintree-php-3.2.0/lib/Braintree.php';


Braintree_Configuration::environment('production');
Braintree_Configuration::merchantId($data['Payment Account ID']);
Braintree_Configuration::publicKey($data['Payment Account Login']);
Braintree_Configuration::privateKey($data['Payment Account Password']);


$smarty->assign('amount', $data['amount']);
$smarty->assign('currency', $data['currency']);
$smarty->assign('order_key', $data['order_key']);
$smarty->assign('braintree_account_key', $data['braintree_account_key']);

$js_files    = array();
$css_files   = array();

$js_files[]  = 'js/jquery.min.js';
$js_files[]  = 'js/braintree.js';
$css_files[] = 'css/font-awesome.min.css';
$css_files[] = 'css/inikoo.css';

$css_files[] = 'css/braintree_paypal.css';

try {
    $braintree_clientToken = Braintree_ClientToken::generate();
} catch (Exception $e) {
    print $e->getMessage();
}
$smarty->assign('braintree_clientToken_paypal', $braintree_clientToken);

$js_files[] = 'js/braintree_paypal.js';



$smarty->assign('css_files', $css_files);
$smarty->assign('js_files', $js_files);

$smarty->display('braintree_paypal_iframe.tpl');

?>
