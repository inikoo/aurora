<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 December 2017 at 11:01:42 GMT, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/new_fork.php';


require_once 'utils/date_functions.php';
require_once 'utils/object_functions.php';


$warehouse = get_object('Warehouse', 1);


$from = date("Y-m-d", strtotime($warehouse->get('Warehouse Valid From')));
$to   = date("Y-m-d", strtotime('now'));


$sql  = "SELECT `Date` FROM kbase.`Date Dimension` WHERE `Date`>=? AND `Date`<=? ORDER BY `Date` DESC";
$stmt = $db->prepare($sql);
$stmt->execute(
    array(
        $from,
        $to
    )
);
while ($row = $stmt->fetch()) {
    new_housekeeping_fork(
        'au_isf', array(
        'date' => $row['Date']
    ), DNS_ACCOUNT_CODE, $db
    );

}

