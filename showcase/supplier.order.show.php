<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 13:57:19 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_supplier_order_showcase($data, $smarty, $user, $db) {



	if (!$data['_object']->id) {
		return "";
	}

	$smarty->assign('order', $data['_object']);

	return $smarty->fetch('showcase/supplier.order.tpl');


}


?>
