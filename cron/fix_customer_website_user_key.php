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



$sql=sprintf('select `Customer Key`,`Customer Store Key`,`Customer Website User Key` from `Customer Dimension`');
if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$customer=get_object('Customer',$row['Customer Key']);
		$store=get_object('Store',$row['Customer Store Key']);
		$website=get_object('Website',$store->get('Store Website Key'));
		$web_user=get_object('Website_User', $customer->get('Customer Website User Key'));


		if($website->id  and $web_user->id and $web_user->get('Website User Website Key')!=$website->id  ){


			$sql = sprintf(
				"SELECT `Website User Key` FROM `Website User Dimension`   WHERE `Website User Handle`=%s  AND `Website User Website Key`=%d ",
				prepare_mysql($customer->get('Customer Main Plain Email')), $website->id
			);





			if ($result2 = $db->query($sql)) {
				if ($row2 = $result2->fetch()) {

						//print_r($row2);

						$customer->fast_update(
							array(
							'Customer Website User Key'=>$row2['Website User Key']
							)
						);

				}

			}

		}



	}
}else {
	print_r($error_info=$db->errorInfo());
	print "$sql\n";
	exit;
}




