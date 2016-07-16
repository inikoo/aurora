<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 8 July 2016 at 01:09:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3.0
*/

function get_showcase($data, $smarty, $user, $db) {



	if (!$data['_object']->id) {
		return "";
	}

$data['_object']->get_order_data();
$data['_object']->update_totals();


	$smarty->assign('delivery', $data['_object']);

	$smarty->assign('object_data', base64_encode(json_encode(
				array(
					'object'=>$data['object'],
					'key'=>$data['key'],
					'order_parent'=>$data['_object']->get('Supplier Delivery Parent'),
					'order_parent_key'=>$data['_object']->get('Supplier Delivery Parent Key')
				)))  );


	if ($data['_object']->get('Purchase Order Submitted Date')!='') {
		$mindate_send_order=1000*date('U', strtotime($data['_object']->get('Purchase Order Submitted Date').' +0:00'));
	}else {
		$mindate_send_order='';


	}
	$smarty->assign('mindate_send_order', $mindate_send_order);


	return $smarty->fetch('showcase/supplier.delivery.tpl');


}


?>
