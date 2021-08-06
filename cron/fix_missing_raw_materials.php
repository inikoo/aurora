<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 02-06-2019 14:10:43 BST  Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/


require_once __DIR__.'/cron_common.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (Set up raw materials)'
);
$store  = get_object('Store', 9);


$account->update_suppliers_data();

$sql  = "select  `Part SKU`   from `Part Dimension` where  `Part Production Supply`='Yes'";
$stmt = $db->prepare($sql);
$stmt->execute();
while ($row = $stmt->fetch()) {
    /**
     * @var $part \Part
     */
    $part = get_object('Part', $row['Part SKU']);
    $part->editor=$editor;

    if($part->get('Part Status')=='Not In Use' or $part->get('Part Status')=='Discontinuing'){
        $part->fast_update(['Part Production Supply'=>'No']);
        $part->fast_update_json_field('Part Properties', 'raw_material_not_active', $part->get('Part Status'));


    }else{
        $part->create_raw_material();

    }


}
