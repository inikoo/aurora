<?php
/*
 *  Author: Raul Perusquia <raul@inikoo.com>
 *  Created: Mon, 13 Jun 2022 19:15:27 Malaysia Time, Kuala Lumpur, Malaysia
 *  Copyright (c) 2022, Inikoo
 *  Version 3.0
 */

/** @var string $dns_host */
/** @var string $dns_port */
/** @var string $dns_db */
/** @var string $dns_user */
/** @var string $dns_pwd */


chdir('../../');

require_once 'keyring/dns.php';

$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

session_start();


$accessKey = '';
if (!empty($_REQUEST['accessKey'])) {
    $accessKey = $_REQUEST['accessKey'];
}

$sql  = "select `Clocking Machine Key` from `Clocking Machine Dimension` where  `Clocking Machine Code`='app-v1' and `Clocking Machine Serial Number`=? ";
$stmt = $db->prepare($sql);
$stmt->execute(
    [
        $accessKey
    ]
);
if ($row = $stmt->fetch()) {
    $_SESSION['apps_clocking_machine_key'] = $row['Clocking Machine Key'];
    setcookie('clocking_app', $row['Clocking Machine Key'].'|'.password_hash($accessKey, PASSWORD_DEFAULT), strtotime('+5 year'));
    echo json_encode(
        [
            'status' => 200,
            'msg'    => 'logged in'
        ]
    );
    exit;
}

echo json_encode(
    [
        'status' => 400,
        'msg'    => 'Invalid access key'
    ]
);