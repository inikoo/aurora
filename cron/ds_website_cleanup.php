<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  06 May 2020  11:59::03  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

require_once 'common.php';


$editor = array(
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Removing not used basket)',
    'Author Alias' => 'System(Removing not used basket)',
);


print date('l jS \of F Y h:i:s A')."\n";

$sql = "select `Page Key`,`Webpage URL` from `Page Store Dimension` left join `Website Dimension` on (`Website Key`=`Webpage Website Key`)  where `Webpage Code`='basket.sys'and `Website Type`='EcomDS'  ";

$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $webpage = get_object('Webpage', $row['Page Key']);
    $webpage->editor;
    $webpage->delete();
}

