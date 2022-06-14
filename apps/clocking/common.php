<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Jun 2022 18:28:56 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

chdir('../../');

require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
require_once 'vendor/autoload.php';
require_once 'utils/sentry.php';
require_once 'keyring/key.php';
include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/cached_objects.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';
require_once 'utils/system_functions.php';
require_once 'utils/network_functions.php';
require_once "utils/aes.php";
require_once "class.Account.php";

$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$smarty = new Smarty();
$smarty->caching_type = 'redis';
$smarty->setTemplateDir('templates');
$smarty->setCompileDir('server_files/smarty/templates_c');
$smarty->setCacheDir('server_files/smarty/cache');
$smarty->setConfigDir('server_files/smarty/configs');
$smarty->addPluginsDir('./smarty_plugins');

session_start();


$clocking_machine_key=false;

if (!empty($_SESSION['apps_clocking_machine_key'])  ) {
    $clocking_machine_key=$_SESSION['apps_clocking_machine_key'];
}elseif(!empty($_COOKIE['clocking_app'])   ){

    $cookie_data=preg_split('/\|/',$_COOKIE['clocking_app']);

    $sql="select `Clocking Machine Serial Number` from `Clocking Machine Dimension` where  `Clocking Machine Key`=? ";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [
            $cookie_data[0]
        ]
    );
    if ($row = $stmt->fetch()) {

        if (password_verify($row['Clocking Machine Serial Number'], $cookie_data[1])) {
            $clocking_machine_key=$cookie_data[0];
        }
    }
}





