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
require 'keyring/au_deploy_conf.php';

require_once 'utils/sentry.php';

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_READ_ONLY_PORT);


include_once('utils/find_website_key.include.php');
$website_key = get_website_key_from_domain($redis);


$url = preg_replace('/^\//', '', $_SERVER['REQUEST_URI']);
$url = preg_replace('/\?.*$/', '', $url);
$url = substr($url, 0, 256);


if ($url == 'search_products_feed.xml') {

    include_once 'feeds/search_products_feed.php';
    exit;

}
elseif ($url == 'search_categories_feed.xml') {

    include_once 'feeds/search_categories_feed.php';
    exit;

}
elseif ($url == 'sitemap.xml') {
    include_once 'utils/public_object_functions.php';

    date_default_timezone_set('UTC');

    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
    $xml .= '<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";


    $website       = get_object('Website', $website_key);
    $site_protocol = 'https';

    $sql = sprintf(
        "select `Sitemap Name` ,`Sitemap Date` from `Sitemap Dimension` where `Sitemap Website Key`=%d",
        $website_key
    );


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
} elseif ($url == 'robots.txt') {
    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    include_once 'utils/public_object_functions.php';


    $website = get_object('Website', $website_key);
    header("Content-Type:text/plain");

    print "User-agent: *\n";
    print "Disallow: /*.pdf$\n";
    print "Disallow: /return_policy\n";
    print "Disallow: /privacy_policy\n";
    print "Disallow: /cookies\n";
    print "Disallow: /attachment.php*\n";
    print "Disallow: /asset_label*\n";
    print "Disallow: /page.php*\n";
    print "Disallow: /*.sys$\n";
    print "Disallow: /ethics\n";
    print "Disallow: /image_root*\n";

    print "\n";

    $name = strtolower(DNS_ACCOUNT_CODE)."_".strtolower(preg_replace('/\./', '', $website->get('Website Code')));
    if ($name == 'aw_awbiz') {
        $name = 'aw';
    }

    print "Sitemap: https://".$website->get('Website URL')."/sitemaps/".$name.".xml.gz\n";
    exit;
} elseif ($url == 'sitemap-info.xml' or $url == 'sitemap-products.xml') {
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
        "SELECT `Sitemap Content` FROM  `Sitemap Dimension` WHERE `Sitemap Website Key`=%d and `Sitemap Name`='%s' ",
        $website_key,
        addslashes($sitemap_name)
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


$url_cache_key = 'pwc2|'.DNS_ACCOUNT_CODE.'|'.$website_key.'_'.$url;

if ($redis->exists($url_cache_key)) {
    $webpage_id = $redis->get($url_cache_key);
    $db         = null;
} else {
    $db = new PDO(
        "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $webpage_id = get_url($db, $website_key, $url);


    $redis_write = new Redis();
    $redis_write->connect(REDIS_HOST, REDIS_PORT);
    $redis_write->set($url_cache_key, $webpage_id);
}


if (is_numeric($webpage_id)) {
    $webpage_key = $webpage_id;
    include 'display_webpage.php';
} else {
    header("Location: ".(ENVIRONMENT == 'DEVEL' ? 'http' : 'https')."://".$_SERVER['SERVER_NAME']."$webpage_id");
}
$db = null;

exit();


function get_url($db, $website_key, $url)
{
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
        return "/404.php?url=$url&original_url=$original_url&w=".$website_key.'&d='.gmdate("Y-m-d\TH:i:s\Z");
    }
}

/**
 * @param $website_key
 * @param $code
 * @param $db \PDO
 *
 * @return int|mixed
 */
function get_page_key_from_code($website_key, $code, $db)
{
    $sql = "SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Website Key`=? AND ( `Webpage Code`=? or `Webpage Canonical Code`=? ) ";

    $stmt = $db->prepare($sql);
    $stmt->execute(
        array(
            $website_key,
            $code,
            $code
        )
    );
    if ($row = $stmt->fetch()) {
        return $row['Page Key'];
    } else {
        return 0;
    }
}