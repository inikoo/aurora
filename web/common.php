<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2016 at 17:25:36 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

error_reporting(E_ALL ^ E_DEPRECATED);
define("_DEVEL", isset($_SERVER['devel']));


require_once 'keyring/dns.php';

include_once 'utils/i18n.php';
require_once 'utils/general_functions.php';
require_once 'utils/natural_language.php';

require_once 'utils/system_functions.php';
require_once 'utils/detect_agent.php';


$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);



setTimezone('UTC');


ini_set('session.gc_maxlifetime', 57600); // 16 hours
ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);
session_start();


require 'external_libs/Smarty/Smarty.class.php';
$smarty               = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir  = 'server_files/smarty/templates_c';
$smarty->cache_dir    = 'server_files/smarty/cache';
$smarty->config_dir   = 'server_files/smarty/configs';
$smarty->assign('_DEVEL', _DEVEL);


$locale = 'en_GB';

set_locale($locale);

$smarty->assign('analyticstracking', (file_exists('templates/analyticstracking.tpl') ? true : false));

?>
