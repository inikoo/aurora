<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 25 December 2017 at 21:55:29 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';


$sql = 'SELECT * FROM `Agent Dimension` ';

if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $sql = "INSERT INTO `Agent Data` (`Agent Key`) VALUES (?)";


        $db->prepare($sql)->execute(
            array(
                $row['Agent Key']
            )
        );

    }

}