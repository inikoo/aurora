<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 June 2017 at 13:15:34 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/
require_once '../vendor/autoload.php';
require 'keyring/dns.php';
require 'keyring/au_deploy_conf.php';

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_READ_ONLY_PORT);

if(empty($_REQUEST['url_KHj321Tu'])){
    echo 'error A';
    exit;
}

$url = $_REQUEST['url_KHj321Tu'];

$html='<script>console.log("test_announcement_v2")</script><div class="tw-bg-red-500 tw-py-1 tw-text-white tw-px-4">Hello World</div>';


header('Content-type: application/json');
echo $html;
