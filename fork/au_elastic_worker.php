<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 May 2020  15:20::54  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/


require_once 'vendor/autoload.php';
require_once "class.Account.php";
require_once 'fork.common.php';
include_once 'utils/object_functions.php';
include_once 'utils/general_functions.php';
include_once 'utils/natural_language.php';
include_once 'elastic.fork.php';
include_once 'keyring/au_deploy_conf.php';

$servers = explode(",", GEARMAN_SERVERS_FOR_WORKERS);
shuffle($servers);
$servers = implode(",", $servers);

$worker = new GearmanWorker();
$worker->addServers($servers);

$worker->addFunction("au_elastic", "fork_elastic");


$db      = false;
$account = false;
$count=0;
while ($worker->work()) {
    if ($count>200 and $worker->returnCode() == GEARMAN_SUCCESS) {
        $db = null;
        exec("kill -9 ".getmypid());
        die();
    }
    $count++;
}

