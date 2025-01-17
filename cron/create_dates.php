<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 December 2015 at 21:39:44 GMT Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'class.Staff.php';
require_once 'utils/date_functions.php';


$from ='2025-01-04';
$to='2025-01-10';

if ($from and $to) {

    $dates = date_range($from, $to);
    foreach ($dates as $date) {
        print "$date\n";

        $sql="insert into kbase.`Date Dimension` (`Date`) values ('$date')";
        $result = $db->query($sql);

    }




}