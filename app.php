<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:27 August 2015 12:49:03 GMT+8, Singapore
 Copyright (c) 2015, Inikoo

 Version 3

*/
/** @var  $smarty Smarty */

require_once 'common.php';
require_once 'utils/timezones.php';
header("Content-Security-Policy-Report-Only: default-src 'self'; report-uri /csp_log.php");


$smarty->assign('_request', $_SERVER['REQUEST_URI']);
$smarty->assign('_side_block', (!empty($_SESSION['side_block']) ? $_SESSION['side_block'] : 'real_time_users'));
$smarty->assign('timezone_info', get_timezone_info());


$smarty->assign('firebase', get_firebase_data());
$smarty->assign('is_devel', ENVIRONMENT == 'DEVEL');

$jira_widget = '';
if (defined('JIRA_WIDGET')) {
    $jira_widget = JIRA_WIDGET;
}
$smarty->assign('jira_widget', $jira_widget);

$jira_portal = '';
if (defined('JIRA_WIDGET')) {
    $jira_portal = JIRA_PORTAL;
}
$smarty->assign('jira_portal', $jira_portal);

$status_page = '';
if (defined('STATUS_PAGE')) {
    $status_page = STATUS_PAGE;
}
$smarty->assign('status_page', $status_page);

$status_page_widget = '';
if (defined('STATUS_PAGE_WIDGET')) {
    $status_page_widget = STATUS_PAGE_WIDGET;
}
$smarty->assign('status_page_widget', $status_page_widget);


try {
    $smarty->display('app.tpl');
} catch (Exception $e) {
}


