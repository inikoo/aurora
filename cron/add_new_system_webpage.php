<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:1 November 2017 at 13:51:23 GMT+8, Plane, Kuala Lumpur - Bali
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/object_functions.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

include_once 'conf/website_system_webpages.php';


//$webpages_to_add = array('favourites.sys');
$webpages_to_add = array('unsubscribe.sys');
$sql = sprintf('SELECT `Website Key` FROM `Website Dimension`');


if ($result = $db->query($sql)) {


    foreach ($result as $row) {

        $website = get_object('Website', $row['Website Key']);


        include_once 'conf/website_system_webpages.php';
        foreach (website_system_webpages_config($website->get('Website Type')) as $key => $website_system_webpages) {
            if (in_array($key, $webpages_to_add)) {
                $webpage=$website->create_system_webpage($website_system_webpages);
                $webpage->publish();
            }


            //$website->create_system_webpage($website_system_webpages);
        }
    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
