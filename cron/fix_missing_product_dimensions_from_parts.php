<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2019 at 17:20:48 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
/** @var PDO $db */

$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System',
    'Author Alias' => 'System (Fix missing properties from part)',


);


$print_est = true;


$sql = 'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`  DESC   ';

$stmt = $db->prepare($sql);
$stmt->execute(
    [

    ]
);
while ($row = $stmt->fetch()) {
    /** @var $part \Part */
    $part         = get_object('Part', $row['Part SKU']);
    $part->editor = $editor;
    print $part->id.' '.$part->get('Reference')."\n";
    $part->updated_linked_products();
}

