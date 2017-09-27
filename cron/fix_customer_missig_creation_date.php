<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2017 at 00:28:29 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';




require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';

$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);



$sql=sprintf('select `Customer Key`, (select `History Date` from `History Dimension` where `Direct Object`="Customer" and `Action`="Created"  and `Direct Object Key`=`Customer Key` ) as date from `Customer Dimension`  where `Customer First Contacted Date` is NULL');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
           $customer=get_object('Customer',$row['Customer Key']);

print_r(array('Customer First Contacted Date'=>$row['date']));


            $customer->update(array('Customer First Contacted Date'=>$row['date']),'no_history');

		}
}else {
		print_r($error_info=$this->db->errorInfo());
		print "$sql\n";
		exit;
}


?>
