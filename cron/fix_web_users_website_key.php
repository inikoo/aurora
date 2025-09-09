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


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Fix Web Users website key)',
    'Author Alias' => 'System (Fix Web Users website key)',
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$sql = sprintf(
    "select `Website User Key`,`Customer Store Key`,`Website User Website Key`,`Store Website Key` from `Website User Dimension`  left join `Customer Dimension` on (`Customer Key`=`Website User Customer Key`) 
left join `Store Dimension` on (`Store Key`=`Customer Store Key`) "
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
       if($row['Store Website Key'] && $row['Website User Website Key']!=$row['Store Website Key']){
            $sql = sprintf(
                "update `Website User Dimension` set `Website User Website Key`=%d where `Website User Key`=%d ",$row['Store Website Key'],$row['Website User Key']
            );
            print "$sql\n";;
          //  $db->exec($sql);
        }

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}