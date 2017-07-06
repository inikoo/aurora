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

$sql = sprintf('SELECT `Page Key` FROM `Page Store Dimension` WHERE `Webpage Code`="register.sys" ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $webpage = new Page($row{'Page Key'});

        $webpage->update(
            array(
                'Webpage Scope Metadata' => json_encode(
                    array(
                        'welcome_email' => array(
                            'key' => '',
                            'published_key'          => false
                        )
                    )
                )
            ), 'no_history'
        );

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
