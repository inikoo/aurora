<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2018 at 04:07:01 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2018, Inikoo

 Version 2.0
*/


require_once 'utils/public_object_functions.php';

require_once __DIR__.'/../vendor/autoload.php';



$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
if (!$id) {
    exit;
}

$redirect_to_login = array(
    'invoice_pdf',
    $id
);
include_once 'ar_web_common_logged_in.php';


$account = get_object('Account', 1);

$smarty = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

$invoice = get_object('Invoice', $id);
if (!$invoice->id) {
    exit;
}

if ($invoice->get('Invoice Customer Key') != $customer->id) {
    exit;
}

require_once 'invoice.pdf.common.php';
