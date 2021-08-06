<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 May 2018 at 12:40:03 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';

$sql  = 'Select `Website Key` from `Website Dimension`';
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {

    foreach(VARNISH_URLS as $varnish_url ) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$varnish_url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "BAN");
        curl_setopt($curl, CURLOPT_PORT, VARNISH_PORT);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


        curl_setopt($curl, CURLOPT_HTTPHEADER, ['x-ban-wk: '.$this->id]);

        curl_exec($curl);

        //print $server_output;
        curl_close($curl);
    }

}

/*
$smarty_web               = new Smarty();
$smarty_web->caching_type = 'redis';
$smarty_web->template_dir = 'EcomB2B/templates';
$smarty_web->compile_dir  = 'EcomB2B/server_files/smarty/templates_c';
$smarty_web->cache_dir    = 'EcomB2B/server_files/smarty/cache';
$smarty_web->config_dir   = 'EcomB2B/server_files/smarty/configs';
$smarty_web->addPluginsDir('./smarty_plugins');
$smarty_web->setCaching(Smarty::CACHING_LIFETIME_CURRENT);


$smarty_web->clearAllCache();

$_redis = new Redis();
$_redis->connect(REDIS_HOST, REDIS_PORT);
$_redis->select(REDIS_SMARTY_CACHE_DB);
$_redis->setOption(Redis::OPT_SCAN, Redis::SCAN_RETRY);


$sql  = 'Select `Website Key` from `Website Dimension`';
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {

    print $row['Website Key']." === \n";
    $it = null;
    while ($arr_keys = $_redis->scan($it)) {
        foreach ($arr_keys as $str_key) {
            if (preg_match('/\|'.$row['Website Key'].'\|'.DNS_ACCOUNT_CODE.'#$/',$str_key)) {
                echo "Here is a key: $str_key\n";
                $_redis->del($str_key);
            }
        }
    }
}

*/
