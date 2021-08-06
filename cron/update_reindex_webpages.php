<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 April 2018 at 14:43:05 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);


//$sql = "SELECT `Page Key` FROM `Page Store Dimension` left join `Website Dimension` on (`Website Key`=`Webpage Website Key`)  where `Website Status`!='Offline'  and `Webpage Scope` !='Product'";
$sql = "SELECT `Page Key` FROM `Page Store Dimension` left join `Website Dimension` on (`Website Key`=`Webpage Website Key`)  where `Website Status`!='Offline'  and `Webpage Scope` ='Category Products' ";
$sql = "SELECT `Page Key` FROM `Page Store Dimension` left join `Website Dimension` on (`Website Key`=`Webpage Website Key`)  where `Website Status`!='Offline'  ";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $webpage = get_object('Webpage', $row['Page Key']);
        print 'Reindex '.$webpage->get('Code')."\n";
        $webpage->reindex();
    }
}