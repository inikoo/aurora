<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 7 November 2018 at 13:53:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$sql=sprintf('select `Order Transaction Fact Key` from `Order Transaction Deal Bridge`');

if ($result=$db->query($sql)) {
		foreach ($result as $row) {



		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}



?>