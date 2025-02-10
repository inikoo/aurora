<?php


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

include 'asset_sales.fork.php';

include_once 'utils/object_functions.php';
include_once 'utils/new_fork.php';


include_once 'keyring/au_deploy_conf.php';
$servers = explode(",", GEARMAN_SERVERS_FOR_WORKERS);
shuffle($servers);
$servers = implode(",", $servers);

$worker = new GearmanWorker();
$worker->addServers($servers);

$worker->addFunction("au_aiku", "fork_aiku_fetch");


$db      = false;
$account = false;

while ($worker->work()) {
    if ($worker->returnCode() == GEARMAN_SUCCESS) {
        $db = null;
        exec("kill -9 ".getmypid());
        die();
    }
}


function fork_aiku_fetch($job)
{
    if (!$_data = get_fork_metadata($job)) {
        return true;
    }

    if (!defined('AIKU_API_URL') || !defined('AIKU_TOKEN'))  {
        return true;
    }


    $account = $_data[0];
    $aiku_organisation_slug = getAikuOrganisation($account->get('Account Code'));

    $url=AIKU_API_URL.$aiku_organisation_slug;

    print_r($_data);

    print "$url t:".AIKU_TOKEN."    \n";









}


function getAikuOrganisation($account_code): string
{
    switch ($account_code) {
        case 'AWEU':
            return 'sk';
        default:
            return strtolower($account_code);
    }
}
