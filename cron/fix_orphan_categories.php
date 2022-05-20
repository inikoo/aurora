<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 14:10:43 BST  Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/


require_once __DIR__.'/cron_common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Fix orphan categories)'
);
$store  = get_object('Store', 9);



$sql  = "select  `Category Key`,`Category Code`   from `Category Dimension`  left join `Part Category Dimension` on (`Category Key`=`Part Category Key`) where  `Category Scope`='Part'  and `Part Category Key` is null ";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {

    print $row['Category Code']."\n";

    $sql = sprintf(
        "INSERT INTO `Part Category Dimension` (`Part Category Key`,`Part Category Valid From`) VALUES (%d,%s)", $row['Category Key'], prepare_mysql(gmdate('Y-m-d H:i:s'))

    );
    $db->exec($sql);


    $sql = $sql = sprintf(
        "INSERT INTO `Part Category Data` (`Part Category Key`) VALUES (%d)",$row['Category Key'],
    );
    $db->exec($sql);



}
