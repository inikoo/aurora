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
    'Author Name'  => 'System (Cleaning spam)',
    'Author Alias' => 'System (Cleaning spam)',
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$sql = sprintf(
    "select `Customer Key` from `Customer Dimension`  where `Customer Registration Number` like '%%_write_here_spammer_filler_%%'  "
);


if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $customer         = new Customer('id', $row['Customer Key']);
        $customer->editor = $editor;

        $customer->data['Customer Name']."\n";

        $customer->delete();

    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}