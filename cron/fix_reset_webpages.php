<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 5 July 2017 at 12:40:22 GMT+8, Cybejaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

if (function_exists('mysql_connect')) {

    $default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
    if (!$default_DB_link) {
        print "Error can not connect with database server\n";
    }
    $db_selected = mysql_select_db($dns_db, $default_DB_link);
    if (!$db_selected) {
        print "Error can not access the database\n";
        exit;
    }
    mysql_set_charset('utf8');
    mysql_query("SET time_zone='+0:00'");

}

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Site.php';
require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.WebsiteNode.php';
require_once 'class.Webpage.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';

require_once 'conf/footer_data.php';
require_once 'conf/header_data.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

include_once 'class.Page.php';

include_once 'class.Website.php';
include_once 'conf/website_system_webpages.php';


$db->exec('truncate `Email Template Dimension`;truncate `Published Email Template Dimension`');


$sql = sprintf('SELECT `Website Key` FROM `Website Dimension`');


if ($result = $db->query($sql)) {


    foreach ($result as $row) {

        $website = new Website($row['Website Key']);





        include_once 'conf/website_system_webpages.php';
        foreach (website_system_webpages_config($website->get('Website Type')) as $website_system_webpages) {


            $website->create_system_webpage($website_system_webpages);
        }
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}




$sql = sprintf('SELECT `Website Header Key` FROM `Website Header Dimension` ');


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $header = get_object('header',$row['Website Header Key']);


        $header->reset();

    }
}

$sql = sprintf('SELECT `Footer Header Key` FROM `Footer Header Dimension` ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $footer = get_object('footer',$row['Footer Header Key']);
        $footer->reset();

    }
}




$sql = sprintf('SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Scope` not in ("Product","Category Categories","Category Products","HomepageToLaunch") ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $webpage = new Page($row{'Page Key'});
        $website = new Website($webpage->get('Webpage Website Key'));
        $website_system_webpages = website_system_webpages_config($website->get('Website Type'));

        $webpage->reset_object();

        if (isset($website_system_webpages[$webpage->get('Webpage Code')]['Webpage Scope Metadata'])) {
            $webpage->update(array('Webpage Scope Metadata' => $website_system_webpages[$webpage->get('Webpage Code')]['Webpage Scope Metadata']), 'no_history');
        }

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
