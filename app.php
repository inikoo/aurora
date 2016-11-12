<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:27 August 2015 12:49:03 GMT+8, Singapure
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';

$smarty->assign('_request', $_SERVER['REQUEST_URI']);
$smarty->assign(
    'show_help', (isset($_SESSION['show_help']) ? $_SESSION['show_help'] : false)
);


require_once 'external_libs/mobile_detect/Mobile_Detect.php';
$detect = new Mobile_Detect;

if ($detect->isMobile()) {
    $display_device_version = 'mobile';
    $detected_device = 'mobile';
} else {
    $display_device_version = 'desktop';
    $detected_device = 'desktop';

}

if (isset($_SESSION['display_device_version']) and $_SESSION['display_device_version'] == 'desktop') {
    $display_device_version = $_SESSION['display_device_version'];

}
$display_device_version='desktop';


$_SESSION['display_device_version']=$display_device_version;
$_SESSION['detected_device']=$detected_device;


if ($display_device_version == 'mobile') {
    $smarty->display('app.mobile.tpl');
} else {
    $smarty->display('app.tpl');
}


?>
