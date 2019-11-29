<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 February 2017 at 13:31:36 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/new_fork.php';



$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);



$sql = "SELECT `Part Category Key`  FROM `Part Category Dimension`   ";

$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {


    $category = get_object('Category',$row['Part Category Key']);


    if ($category->id and $category->get('Category Scope') == 'Part') {
        new_housekeeping_fork(
            'au_redo_time_series', array(
            'object' => 'PartCategory',
            'key'    => $row['Part Category Key'],
            'editor'=>$editor

        ), $account->get('Account Code'), $db
        );


    }
}
