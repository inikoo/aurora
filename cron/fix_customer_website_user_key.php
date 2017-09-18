<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:18 September 2017 at 14:19:17 GMT+8, Kuala Lumpur, Malaysia
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



$sql=sprintf('select `Website User Key`,`Website User Customer Key` from `Website User Dimension`');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
           $customer=get_object('Customer',$row['Website User Customer Key']);

            $customer->update(
                array(
                    'Customer Website User Key'=>$row['Website User Key'],
                  
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
