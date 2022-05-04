<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Tue, 03 May 2022 10:06:28 Central European Summer Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

/** @var Order $order */

/** @var PDO $db */
include_once 'ar_web_common_logged_in.php';
include_once 'hokodo/api_call.php';
include_once 'hokodo/get_plans.php';

/** @var Public_Customer $customer */


$website = get_object('Website', $_SESSION['website_key']);
$api_key = $website->get_api_key('Hokodo');

$account = get_object('Account', 1);

$res = get_plans($db, $order, $customer, $website);

echo json_encode($res);
exit;

