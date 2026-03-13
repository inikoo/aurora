<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 Mar 2026 00:01 Bali Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$sql = "SELECT `Page Key` FROM `Page Store Dimension`";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    $webpage = get_object('Webpage', $row['Page Key']);

    $webpage->fast_update(array('browser_title'=>$webpage->get_browser_title()));

}
