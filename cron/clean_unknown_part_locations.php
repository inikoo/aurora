<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 29 January 2018 at 12:51:52 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Part.php';
require_once 'class.PartLocation.php';



$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'=>'System',
    'Subject Key'=>0,
    'Author Name'=>'Script (clean zero Unk PL)'
);


$sql=sprintf('select `Part SKU` from `Part Location Dimension` where `Location Key`=1 and `Quantity On Hand`=0 and `Quantity In Process`=0 ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
           $part_location=new PartLocation($row['Part SKU'].'_1');
           $part_location->editor=$editor;
           $part_location->disassociate();
         
		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}




?>
