<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 31 July 2018 at 22:19:01 GMT+8
 Copyright (c) 2018, Inikoo

 Version 3

*/

chdir('../');

require_once 'vendor/autoload.php';
require_once 'utils/sentry.php';

require_once 'keyring/dns.php';
require_once 'keyring/key.php';
require_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/object_functions.php';

require_once "class.Account.php";

if (class_exists('Memcached')) {
    $mem = new Memcached();
    $mem->addServer($memcache_ip, 11211);
}

$db = new PDO(
    "mysql:host=$dns_host;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);



class fake_session {
    function __construct() {
        $this->data = array();
    }

    function set($key, $value) {
        $this->data[$key] = $value;
    }

    function get($key) {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        } else {
            return false;
        }
    }
}

$session = new fake_session;

$warehouse_key = '';
$sql           = sprintf('SELECT `Warehouse Key` FROM `Warehouse Dimension` WHERE `Warehouse State`="Active" limit 1');

if ($result2 = $db->query($sql)) {
    if ($row2 = $result2->fetch()) {
        $warehouse_key = $row2['Warehouse Key'];
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}
$session->set('current_warehouse', $warehouse_key);


/*
if(function_exists('mysql_connect')) {

    $default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
    if (!$default_DB_link) {
        print "Error can not connect with database server\n";
    }
    $db_selected = @mysql_select_db($dns_db, $default_DB_link);
    if (!$db_selected) {
        print "Error can not access the database\n";
        exit;
    }
    mysql_set_charset('utf8');
    mysql_query("SET time_zone='+0:00'");

}
*/

$account = new Account($db);





date_default_timezone_set($account->data['Account Timezone']);


?>
