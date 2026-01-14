<?php
/**
 * CSP violation logger
 */

exit();
require_once 'vendor/autoload.php';

include_once 'utils/object_functions.php';


include_once 'keyring/dns.php';
include_once 'keyring/au_deploy_conf.php';
include_once 'keyring/key.php';
$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$data = file_get_contents('php://input');

if ($data) {
    $decoded = json_decode($data, true);
    if ($decoded) {
        $log_entry = json_encode($decoded, JSON_PRETTY_PRINT);
    } else {
        $log_entry = $data;
    }


    $sql  = "insert into  debugtable (text, date) values (?, NOW())";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        [$log_entry]
    );

}

header('HTTP/1.1 204 No Content');
