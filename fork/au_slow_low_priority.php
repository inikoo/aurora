<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24-09-2019 01:14:20 MYT, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

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
include 'slow_low_priority.fork.php';



$worker = new GearmanWorker();
$worker->addServer('127.0.0.1');
$worker->addFunction("au_take_webpage_screenshot", "fork_take_webpage_screenshot");
$worker->addFunction("au_redo_time_series", "fork_redo_time_series");

$db      = false;
$account = false;

while ($worker->work()) {
    if ($worker->returnCode() == GEARMAN_SUCCESS) {
        $db = null;
        exec("kill -9 ".getmypid());
        die();
    }
}

