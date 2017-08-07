<?php
/*

 About:
 Author: Raul Perusquia <rulovico@gmail.com>
 Created: 8 May 2017 at 22:57:54 GMT-5, CDMX, Mexico

 Copyright (c) 2017, Inikoo

 Version 2.0
*/


//error_reporting(E_ALL ^ E_DEPRECATED);


require_once 'keyring/dns.php';


//date_default_timezone_set(TIMEZONE);

$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);


include ('utils/find_website_key.include.php');


$result = $mem->get('ECOMP'.md5($_SERVER['SERVER_NAME'].'_'.$_SERVER['REQUEST_URI']));
$result = false;
if (!$result ) {




    $result = get_url($_SESSION['website_key'], $_SERVER['REQUEST_URI'], $dns_host, $dns_user, $dns_pwd, $dns_db);
    $mem->set('ECOMP'.md5($_SERVER['SERVER_NAME'].'_'.$_SERVER['REQUEST_URI']), $result, 172800);
}



if (is_numeric($result)) {

    include_once 'common.php';
    $webpage_key = $result;
    include 'webpage.php';
    exit;
} else {

    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
        $protocol = 'https';
    } else {
        $protocol = 'http';
    }



    header("Location: $protocol://".$_SERVER['SERVER_NAME']."$result");
    exit;

}


function get_url($site_key, $url, $dns_host, $dns_user, $dns_pwd, $dns_db) {


    $db = new PDO(
        "mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
    );
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


    $url = preg_replace('/^\//', '', $url);
    $url = preg_replace('/\?.*$/', '', $url);


    $original_url = $url;
    $page_key     = get_page_key_from_code($site_key, $url, $db);
    if ($page_key) {
        return $page_key;
    }

    if (!$page_key and preg_match('/[a-z0-9\_\-]\/$/i', $url)) {
        $_tmp_url = preg_replace('/\/$/', '', $url);
        $page_key = get_page_key_from_code($site_key, $_tmp_url, $db);
        if ($page_key) {
            return $page_key;

            //$url=$_SERVER['SERVER_NAME'].'/'.$_tmp_url;
            //return $url;
        }
    }


    if (preg_match('/[a-z0-9\_\-]\/$/i', $url)) {
        return $_SERVER['HTTP_HOST'].'/index.php?error='.$_tmp_url;
        //$_tmp_url=preg_replace('/\/$/','',$url);
        //exit("$_tmp_url");
        //header("Location: http://".$target);
    }
    $sql = sprintf(
        "SELECT `Site URL` FROM `Site Dimension` WHERE `Site Key`=%d ", $site_key
    );


    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $site_url = $row['Site URL'];
        }
    }


    $original_url = '/'.$original_url;

    $url_array    = explode("/", $url);
    $file         = array_pop($url_array);

    if (preg_match('/\.(php|html)$/', $file)) {
        $path = join('/', $url_array);
    } else {
        $file = 'index.php';
        $path = $url;
    }



    if (preg_match('/^sitemap\.xml$/', $url, $match)) {



        return '/sitemap_index.xml.php';

    }


    if (preg_match('/^sitemap(\d+)\.xml$/', $url, $match)) {
        $sitemap_key = $match[1];




        return '/sitemap.xml.php?id='.$sitemap_key;

    }
    $sql = sprintf(
        "SELECT  `Webpage Alias Webpage Key` FROM `Webpage Alias Dimension` WHERE `Webpage Alias Website Key`=%d AND `Webpage Alias Tag`=%s ",
        SITE_KEY,


        _prepare_mysql($url)
    );

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {

                return $row['Webpage Alias Webpage Key'];



        } else {



            return "/404.php?url=$url&original_url=$original_url";
        }
    }


}

function get_page_key_from_code($site_key, $code, $db) {

    $page_key = 0;
    $sql      = sprintf(
        "SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Website Key`=%d AND `Webpage Code`=%s ", $site_key, _prepare_mysql($code)
    );




    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $page_key = $row['Page Key'];
        }
    }


    return $page_key;
}


function _prepare_mysql($string, $null_if_empty = true) {

    if ($string == '' and $null_if_empty) {
        return 'NULL';
    } else {
        return "'".addslashes($string)."'";
    }
}


?>
