<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  12 March 2020  13:53::15  +0800 Kuala Lumpur, Malaysia
 Copyright (c) 2020, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


$sql  = "select `Store Key`,`Store Website Key`,`Store Department Category Key` from `Store Dimension` ";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {

    $website = get_object('Website', $row['Store Website Key']);

    if ($website->id and $website->get('Website Status') == 'Active') {


        $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';

        $date = gmdate('Y-m-d H:i:s');

        $db->prepare($sql)->execute(
            [
                $date,
                $date,
                'store_data_feed',
                $row['Store Key'],
                $date,

            ]
        );

        $sql   = "select `Category Key` from `Category Dimension` where `Category Root Key`=?";
        $stmt2 = $db->prepare($sql);
        $stmt2->execute(
            array(
                $row['Store Department Category Key']
            )
        );
        while ($row2 = $stmt2->fetch()) {

            $sql = 'insert into `Stack Dimension` (`Stack Creation Date`,`Stack Last Update Date`,`Stack Operation`,`Stack Object Key`) values (?,?,?,?) 
                      ON DUPLICATE KEY UPDATE `Stack Last Update Date`=? ,`Stack Counter`=`Stack Counter`+1 ';
            $date = gmdate('Y-m-d H:i:s');

            $db->prepare($sql)->execute(
                [
                    $date,
                    $date,
                    'department_data_feed',
                    $row2['Category Key'],
                    $date,

                ]
            );
        }


    }
}

