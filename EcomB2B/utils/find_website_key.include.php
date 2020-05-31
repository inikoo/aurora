<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2017 at 20:17:49 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

/**
 * @param $redis \Redis
 *
 * @return int
 */
function get_website_key_from_domain($redis) {
    $redis_key = 'GET_WK2'.$_SERVER['SERVER_NAME'];
    if ($redis->exists($redis_key)) {
        return $redis->get($redis_key);
    } else {
        if (ENVIRONMENT=='DEVEL') {

            include 'keyring/dns.php';
            include 'keyring/au_deploy_conf.php';

            $redis_write = new Redis();
            $redis_write->connect(REDIS_HOST, REDIS_PORT);

            $redis_write->set($redis_key, DEVEL_WEBSITE_KEY);
            return DEVEL_WEBSITE_KEY;
        } else {

            /** @var $dns_host string */
            /** @var $dns_db string */
            /** @var $dns_user string */
            /** @var $dns_pwd string */
            /** @var $dns_port string */
            require 'keyring/dns.php';
            include 'keyring/au_deploy_conf.php';

            $db = new PDO(
                "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
            );
            $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $sql = "SELECT `Website Key`  FROM `Website Dimension` WHERE `Website URL`=?";

            $stmt = $db->prepare($sql);
            $stmt->execute(
                array(
                    $_SERVER['SERVER_NAME']
                )
            );
            if ($row = $stmt->fetch()) {

                $redis_write = new Redis();
                $redis_write->connect(REDIS_HOST, REDIS_PORT);
                $redis_write->set($redis_key, $row['Website Key']);
                return $row['Website Key'];
            } else {
                Sentry\captureMessage('Can not find website key from '.$_SERVER['SERVER_NAME']);
                exit;
            }
        }
    }
}