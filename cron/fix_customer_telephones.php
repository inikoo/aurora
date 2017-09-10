<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 9 September 2017 at 14:48:33 GMT+8, Kuala Lumpur, Malaysia
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



$sql=sprintf('select `Customer Key` from `Customer Dimension`');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
           $customer=get_object('Customer',$row['Customer Key']);

            $customer->update(
                array(
                    'Customer Main Plain Mobile'=>$customer->get('Customer Main Plain Mobile'),
                    'Customer Main Plain Telephone'=>$customer->get('Customer Main Plain Telephone'),
                    'Customer Main Plain FAX'=>$customer->get('Customer Main Plain FAX'),
                ),
                'no_history'

            );

		}
}else {
		print_r($error_info=$this->db->errorInfo());
		print "$sql\n";
		exit;
}


?>
