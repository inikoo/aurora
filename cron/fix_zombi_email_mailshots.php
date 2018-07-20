<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 June 2018 at 13:38:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

// When is a failure in SES fork mailshots can create zombies

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';




$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);



$sql=sprintf('select `Email Campaign Key` from `Email Campaign Dimension` where `Email Campaign State`="Sending" ');
if ($result2=$db->query($sql)) {
		foreach ($result2 as $row2) {

            $sql = sprintf('SELECT `History Key` FROM `Email Campaign History Bridge` WHERE `Email Campaign Key`=%d ', $row2['Email Campaign Key']);

            if ($result = $db->query($sql)) {
                foreach ($result as $row) {
                    $sql = sprintf("DELETE FROM `History Dimension` WHERE  `History Key`=%d", $row['History Key']);
                    $db->exec($sql);
                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }

            $sql = sprintf("DELETE FROM `Email Campaign History Bridge` WHERE `Email Campaign Key`=%d ", $row2['Email Campaign Key']);
            $db->exec($sql);

            $sql = sprintf("DELETE FROM `Email Tracking Dimension` WHERE `Email Tracking Email Mailshot Key`=%d ", $row2['Email Campaign Key']);
            $db->exec($sql);

            $sql = sprintf(
                "DELETE FROM `Email Campaign Dimension` WHERE `Email Campaign Key`=%d", $row2['Email Campaign Key']
            );
            $db->exec($sql);

		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}
