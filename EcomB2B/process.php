<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 8 May 2017 at 22:57:54 GMT-5, CDMX, Mexico

 Copyright (c) 2017, Inikoo

 Version 2.0
*/



require_once '../vendor/autoload.php';
require 'keyring/dns.php';
require_once 'utils/sentry.php';


$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_READ_ONLY_PORT);

session_start();



if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}




$url = preg_replace('/^\//', '', $_SERVER['REQUEST_URI']);
$url = preg_replace('/\?.*$/', '', $url);
$url = substr($url, 0, 256);


if ($url == 'sitemap.xml') {


    include_once 'utils/public_object_functions.php';

    date_default_timezone_set('UTC');

    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";
    $sql = sprintf(
        "select `Sitemap Name` ,`Sitemap Date` from `Sitemap Dimension` where `Sitemap Website Key`=%d", $_SESSION['website_key']
    );

    $website = get_object('Website', $_SESSION['website_key']);


    $site_protocol = 'https';

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $xml .= '  <sitemap>'."\n";
            $xml .= '    <loc>https://'.$website->get('Website URL').'/'.preg_replace('/-1\.xml\.gz/', '', $row['Sitemap Name']).'.xml</loc>'."\n";
            $xml .= '    <lastmod>'.date('Y-m-d', strtotime($row['Sitemap Date'])).'</lastmod>'."\n";
            $xml .= '  </sitemap>'."\n";
        }
    }


    $xml .= '</sitemapindex>'."\n";

    header("Content-Type:text/xml");
    print $xml;
    exit;

}
elseif ($url == 'sitemap-info.xml' or $url == 'sitemap-products.xml') {


    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    if ($url == 'sitemap-info.xml') {
        $sitemap_name = 'sitemap-info-1.xml.gz';
    } elseif ($url == 'sitemap-products.xml') {
        $sitemap_name = 'sitemap-products-1.xml.gz';
    }


    $sql = sprintf(
        "SELECT `Sitemap Content` FROM  `Sitemap Dimension` WHERE `Sitemap Website Key`=%d and `Sitemap Name`='%s' ", $_SESSION['website_key'], addslashes($sitemap_name)
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $xml = $row['Sitemap Content'];
        } else {
            $xml = '';
        }
    }


    header("Content-Type:text/xml");
    print $xml;
    if (isset($db)) {
        $db = null;
    }
    exit;


}


$url_cache_key = 'pwc2|'.DNS_ACCOUNT_CODE.'|'.$_SESSION['website_key'].'_'.$url;

if ($redis->exists($url_cache_key)) {
    $webpage_id = $redis->get($url_cache_key);
} else {
    $webpage_id = get_url($_SESSION['website_key'], $url, $dns_host, $dns_user, $dns_pwd, $dns_db,$dns_port);
    $redis_write = new Redis();
    $redis_write->connect(REDIS_HOST, REDIS_PORT);
    $redis_write->set($url_cache_key, $webpage_id);
}


if (is_numeric($webpage_id)) {
    $website_key = $_SESSION['website_key'];
    $webpage_key = $webpage_id;


    include 'display_webpage.php';
} else {
    header("Location: https://".$_SERVER['SERVER_NAME']."$webpage_id");
}
if (isset($db)) {
    $db = null;
}
exit();


function get_url($website_key, $url, $dns_host, $dns_user, $dns_pwd, $dns_db,$dns_port) {


    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $original_url = $url;
    $page_key     = get_page_key_from_code($website_key, $url, $db);
    if ($page_key) {
        return $page_key;
    }
    $_tmp_url = preg_replace('/\/$/', '', $url);

    if (!$page_key and preg_match('/[a-z0-9_\-]\/$/i', $url)) {
        $page_key = get_page_key_from_code($website_key, $_tmp_url, $db);
        if ($page_key) {
            return $page_key;
        }
    }


    if (preg_match('/[a-z0-9_\-]\/$/i', $url)) {
        return $_SERVER['HTTP_HOST'].'/index.php?error='.$_tmp_url;
    }


    $original_url = '/'.$original_url;


    if (preg_match('/^sitemap(\d+)\.xml$/', $url, $match)) {
        $sitemap_key = $match[1];


        if ($sitemap_key == 0) {
            return '/sitemap_index.xml.php';

        } else {
            return '/sitemap.xml.php?id='.$sitemap_key;
        }


    }

    $sql  = "SELECT  `Webpage Alias Webpage Key` FROM `Webpage Alias Dimension` WHERE `Webpage Alias Website Key`=? AND `Webpage Alias Tag`=?";
    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $website_key,
            $url
        )
    );
    if ($row = $stmt->fetch()) {
        return $row['Webpage Alias Webpage Key'];

    } else {
        return "/404.php?url=$url&original_url=$original_url";

    }


}

/**
 * @param $website_key
 * @param $code
 * @param $db \PDO
 *
 * @return int|mixed
 */
function get_page_key_from_code($website_key, $code, $db) {

    $sql = "SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Website Key`=? AND `Webpage Code`=? ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $website_key,
            $code
        )
    );
    if ($row = $stmt->fetch()) {
        return $row['Page Key'];
    } else {
        return 0;
    }


}