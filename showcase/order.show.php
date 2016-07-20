<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2015 at 10:19:15 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_order_showcase($data) {

	global $smarty, $user;

	if (!$data['_object']->id) {
		return "";
	}

	$smarty->assign('order', $data['_object']);

	$order=$data['_object'];

	$dns_data=array();
	foreach ($order->get_delivery_notes_objects() as $dn) {
		$current_delivery_note_key=$dn->id;

		$missing_dn_data=false;
		$missing_dn_str='';
		$dn_data='';
		if ($dn->data['Delivery Note Weight']) {
			$dn_data=$dn->get('Weight');
		}else {
			$missing_dn_data=true;
			$missing_dn_str=_('weight');

		}

		if ($dn->data['Delivery Note Number Parcels']!='') {
			$dn_data.=', '.$dn->get_formatted_parcels();
		}else {
			$missing_dn_data=true;
			$missing_dn_str.=', '._('parcels');
		}
		$missing_dn_str=preg_replace('/^,/', '', $missing_dn_str);


		if ($dn->data['Delivery Note Shipper Consignment']!='') {
			$dn_data.=', '. $dn->get('Consignment');
		}else {
			$missing_dn_data=true;
			$missing_dn_str.=', '._('consignment');
		}
		$missing_dn_str=preg_replace('/^,/', '', $missing_dn_str);
		$dn_data=preg_replace('/^,/', '', $dn_data);

		if ($missing_dn_data  and in_array($order->data['Order Current Dispatch State'], array('Packed Done', 'Packed')) ) {
			$dn_data.=' <img src="art/icons/exclamation.png" style="height:14px;vertical-align:-3px"> <span style="font-style:italic;color:#ea6c59">'._('Missing').': '.$missing_dn_str.'</span> <img onClick="show_dialog_set_dn_data_from_order('.$dn->id.')" style="cursor:pointer;display:none" src="art/icons/edit.gif"> ';
		}

		$dns_data[]=array(
			'key'=>$dn->id,
			'number'=>$dn->data['Delivery Note ID'],
			'state'=>$dn->data['Delivery Note XHTML State'],
			'dispatch_state'=>$dn->data['Delivery Note State'],
			'data'=>$dn_data,
			'operations'=>$dn->get_operations($user, 'order', $order->id),
		);

		//print_r($dns_data);

	}
	$number_dns=count($dns_data);
	if ($number_dns!=1) {
		$current_delivery_note_key='';
	}
	$smarty->assign('current_delivery_note_key', $current_delivery_note_key);
	$smarty->assign('number_dns', $number_dns);
	$smarty->assign('dns_data', $dns_data);


	$invoices_data=array();
	foreach ($order->get_invoices_objects() as $invoice) {
		$current_invoice_key=$invoice->id;


		$invoices_data[]=array(
			'key'=>$invoice->id,
			'operations'=>$invoice->get_operations($user, 'order', $order->id),
			'number'=>$invoice->data['Invoice Public ID'],
			'state'=>$invoice->get_formatted_payment_state(),
			'to_pay'=>$invoice->data['Invoice Outstanding Total Amount'],
			'data'=>'',


		);
	}

	$number_invoices=count($invoices_data);
	if ($number_invoices!=1) {
		$current_invoice_key='';
	}
	$smarty->assign('current_invoice_key', $current_invoice_key);
	$smarty->assign('number_invoices', $number_invoices);
	$smarty->assign('invoices_data', $invoices_data);


	$smarty->assign('object_data', base64_encode(json_encode(
					array(
						'object'=>$data['object'],
						'key'=>$data['key'],

						'tab'=>$data['tab']
					)))  );


	return $smarty->fetch('showcase/order.tpl');



}


?>
