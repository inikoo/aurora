<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:18:30:10 MYT Thursday, 9 July 2020, Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

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


include_once 'keyring/au_deploy_conf.php';
$servers = explode(",", GEARMAN_SERVERS_FOR_WORKERS);
shuffle($servers);
$servers = implode(",", $servers);

$worker = new GearmanWorker();
$worker->addServers($servers);

$worker->addFunction("au_isf", "fork_isf");


$db      = false;
$account = false;

while ($worker->work()) {
    if ($worker->returnCode() == GEARMAN_SUCCESS) {
        $db = null;
        exec("kill -9 ".getmypid());
        die();
    }
}


function fork_isf($job) {
    global $account, $db;// remove the global $db and $account is removed


    if (!$_data = get_fork_metadata($job)) {
        return true;
    }


    $account=$_data[0];
    $db=$_data[1];
    $data=$_data[2];

    print 'ISF: '.$account->get('Code').' '.$data['date']."\n";


    $sql  = "SELECT `Part SKU` FROM `Part Dimension` ";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        /** @var $part \Part */
        $part = get_object('Part', $row['Part SKU']);
        $part->update_part_inventory_snapshot_fact($data['date'], $data['date']);
    }


    $sql  = "SELECT `Warehouse Key` FROM `Warehouse Dimension`";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
        $warehouse = get_object('Warehouse',$row['Warehouse Key']);
        $warehouse->update_inventory_snapshot($data['date']);
    }


}
