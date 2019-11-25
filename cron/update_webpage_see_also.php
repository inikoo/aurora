<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 April 2018 at 14:43:05 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$sql = "SELECT `Page Key` FROM `Page Store Dimension`  left join `Website Dimension` on (`Website Key`=`Webpage Website Key`)  where `Website Theme`='theme_1'";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $webpage = get_object('Webpage', $row['Page Key']);
    print $webpage->id.' '.$webpage->get('Webpage URL')."\n";
    $webpage->refill_see_also(false, 5);
}
