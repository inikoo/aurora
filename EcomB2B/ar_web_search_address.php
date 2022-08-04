<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Fri, 17 Jun 2022 13:04:41 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

include_once '../vendor/autoload.php';
require_once 'utils/sentry.php';
require_once 'keyring/dns.php';
require_once 'keyring/au_deploy_conf.php';
require_once 'keyring/key.php';
include_once 'utils/public_object_functions.php';

include_once 'utils/natural_language.php';
include_once 'utils/general_functions.php';
include_once 'utils/network_functions.php';
include_once __DIR__.'/utils/web_common.php';

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);
session_start();
if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}

if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 407,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}



$logged_in =get_logged_in();

if (!isset($db) or is_null($db) ) {

    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
$website=get_object('Website',$_SESSION['website_key']);
$account=get_object('Account',1);
require_once 'utils/ar_web_common.php';



include_once 'Capture_Interactive_Find_v1_10.php';


$is_middleware = 'False';
if (ENVIRONMENT == 'DEVEL') {
    $is_middleware = 'True';
}


$container='';
if(!empty( $_REQUEST['container'])){
    $container=$_REQUEST['container'];
}


$pa = new Capture_Interactive_Find_v1_10 (AU_LOQATE_KEY, $_REQUEST['query'], $is_middleware, $container, $_REQUEST['country'], "", $_REQUEST['limit'], "", "", "", "");

$res = [];
$pa->MakeRequest();
if ($pa->HasData()) {
    $data = $pa->HasData();


    foreach ($data as $item) {
        $res[] = [

            'value' => (string)$item['Text'][0].' '.(string)$item['Description'][0],

            'data' => [
                'id'   => (string)$item['Id'][0],
                'type' => (string)$item['Type'][0],
                'value'=>(string)$item['Text'][0],
            ]


        ];
    }
}


echo json_encode([
                     'suggestions' => $res
                 ]);

