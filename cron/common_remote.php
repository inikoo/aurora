<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:6 December 2015 at 21:40:13 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


chdir('../');

require_once 'keyring/dns_remote.php';
require_once 'keyring/key.php';
require_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/object_functions.php';

require_once "class.Account.php";

$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$account = new Account($db);

date_default_timezone_set($account->data['Account Timezone']);

