<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 July 2018 at 22:19:01 GMT+8
 Copyright (c) 2018, Inikoo

 Version 3

*/

chdir('../');
/** @var string $dns_host */
/** @var string $dns_port */
/** @var string $dns_db */
/** @var string $dns_user */
/** @var string $dns_pwd */

require_once 'vendor/autoload.php';
require_once 'utils/sentry.php';
require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
require_once 'keyring/key.php';
require_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/object_functions.php';
require_once "class.Account.php";


$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);

$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$warehouse_key = '';

$sql = "SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`='Active' limit 1";

if ($result2 = $db->query($sql)) {
    if ($row2 = $result2->fetch()) {
        $warehouse_key = $row2['Warehouse Key'];
    }
}
$_SESSION['current_warehouse'] = $warehouse_key;


$account = new Account($db);
date_default_timezone_set($account->data['Account Timezone']);

