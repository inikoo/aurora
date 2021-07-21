<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 24 March 2016 at 13:57:44 GMT+8, Kuala Lumpur, Malaysia
 Created: 2013
 Copyright (c) 2016, Inikoo

 Version 3

*/

error_reporting(E_ALL ^ E_DEPRECATED);

require_once 'vendor/autoload.php';
require_once "class.Account.php";
require_once 'fork.common.php';

include_once 'utils/object_functions.php';

include 'utils/aes.php';
include 'utils/general_functions.php';
include 'utils/system_functions.php';
include 'utils/natural_language.php';
include 'asset_sales.fork.php';


include 'housekeeping.fork.php';
include 'time_series.fork.php';
include 'calculate_sales.fork.php';

include_once 'keyring/au_deploy_conf.php';
$servers = explode(",", GEARMAN_SERVERS_FOR_WORKERS);
shuffle($servers);
$servers = implode(",", $servers);

$worker = new GearmanWorker();
$worker->addServers($servers);

$worker->addFunction("au_housekeeping", "fork_housekeeping");
$worker->addFunction("au_time_series", "fork_time_series");
$worker->addFunction("au_calculate_sales", "fork_calculate_sales");

$db      = false;
$account = false;

while ($worker->work()) {
    if ($worker->returnCode() == GEARMAN_SUCCESS) {
        $db = null;
        exec("kill -9 ".getmypid());
        die();
    }
}

