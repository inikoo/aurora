<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:14 August 2018 at 13:29:12 GMT+8, Kuta, Bali, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$sql = sprintf('update `Invoice Dimension` set `Invoice Sales Representative Key`=NULL');
print "$sql\n";
$db->exec($sql);



$sql = sprintf('select `Customer Sales Representative Key`,`Customer Key` from `Customer Dimension` where `Customer Sales Representative Key`>0 ');
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $sql = sprintf('update `Invoice Dimension` set `Invoice Sales Representative Key`=%d where `Invoice Customer Key`=%d and `Invoice Date`>"2017-01-01 00:00:00" ', $row['Customer Sales Representative Key'], $row['Customer Key']);
        print "$sql\n";
        $db->exec($sql);

        $sql = sprintf('update `Order Dimension` set `Order Sales Representative Key`=%d where `Order Customer Key`=%d and `Order Date`>"2017-01-01 00:00:00" ', $row['Customer Sales Representative Key'], $row['Customer Key']);
        print "$sql\n";
        $db->exec($sql);

    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


?>
