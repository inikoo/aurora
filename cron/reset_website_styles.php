<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 April 2018 at 15:20:11 BST, Sheffield, UK
 Copyright (c) 2018, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'conf/website_styles.php';


$sql = sprintf('select `Website Key` from `Website Dimension`   ');

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $website = get_object('Website', $row['Website Key']);

        $website->fast_update(
            array(
                'Website Style' => json_encode($website_styles)
            )
        );


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

