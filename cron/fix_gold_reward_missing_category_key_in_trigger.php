<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 December 2017 at 09:34:36 CET, MIjas Costa, Spain
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
include_once 'utils/get_addressing.php';

require_once 'class.Customer.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$where = '  ';




$sql = sprintf(
    "select * from `Deal Component Dimension` WHERE (`Deal Component Trigger` = 'Category') AND (`Deal Component Trigger Key` = 0)  and
                                        (`Deal Component Allowance Target` = 'Category')     AND (`Deal Component Allowance Target Key` > 0)   "
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $familyCode=$row['Deal Component Allowance Target Label'];

        $sql="update  `Deal Component Dimension` set `Deal Component Trigger Key` = `Deal Component Allowance Target Key`  where `Deal Component Key`=? ";

        $db->prepare($sql)->execute(
            array(
                $row['Deal Component Key']
            )
        );




    }
}
