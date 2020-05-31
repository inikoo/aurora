<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2017 at 17:22:49 GMT,  Sheffield UK
 Copyright (c) 2017, Inikoo

 Version 3

*/


chdir('../');

require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
require_once 'keyring/key.php';
require_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once "class.Account.php";


$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$account = new Account($db);
date_default_timezone_set($account->data['Account Timezone']);


