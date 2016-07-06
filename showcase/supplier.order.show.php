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

	$smarty->assign('object_data', base64_encode(json_encode(
				array(
					'object'=>$data['object'],
					'key'=>$data['key'],
					'order_parent'=>$data['_object']->get('Purchase Order Parent'),
					'order_parent_key'=>$data['_object']->get('Purchase Order Parent Key')
				)))  );


	if ($data['_object']->get('Purchase Order Submitted Date')!='') {
		$mindate_send_order=date('U', strtotime($data['_object']->get('Purchase Order Submitted Date').' +0:00'));
	}else {
		$mindate_send_order=date('U', strtotime($data['_object']->get('Purchase Order Created Date').' +0:00'));


	}
	$smarty->assign('mindate_send_order', 1000*$mindate_send_order);



	return $smarty->fetch('showcase/supplier.order.tpl');


}


?>
