<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:6 October 2015 at 21:26:35 BST, Birminham->Malaga (Plane)
 Copyright (c) 2015, Inikoo

 Version 3

*/


$order=$state['_object'];

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'Order_Key',
				'value'=>$order->id  ,
				'label'=>_('Id')
			),
            array(
				'class'=>'locked',
				'id'=>'Order_Public_ID',
				'value'=>$order->get('Order Public ID')  ,
				'label'=>_('Number')
			),
			
			
			

		)
	),
	array(
		'label'=>_('Customer'),
		'show_title'=>true,
		'fields'=>array(
			
			array(
				'class'=>'locked',
				'id'=>'Cutomer',
				'value'=>$order->get('Order Customer Name').' (<span class="id">'.sprintf("%05d",$order->get('Order Customer Key')).'</span>)',
				'label'=>_('Customer')
			),
			 array(
				'id'=>'Order_Customer_Fiscal_Name',
				'value'=>$order->get('Order Customer Fiscal Name')  ,
				'label'=>_('Fiscal name')
			),array(
				'id'=>'Order_Customer_Contact_Name',
				'value'=>$order->get('Order Customer Contact Name')  ,
				'label'=>_('Contact name')
			),array(
				'id'=>'Order_Telephone',
				'value'=>$order->get('Order Telephone')  ,
				'label'=>_('Contact telephone')
			),array(
				'id'=>'Order_Email',
				'value'=>$order->get('Order Email')  ,
				'label'=>_('Contact email')
			),
			
			

		)
	),
	array(
		'label'=>_('Billing'),
		'show_title'=>true,
		'fields'=>array(
			
            array(
				'class'=>'address',
				'id'=>'Order_Billing_Address',
				'value'=>$order->get('Order XHTML Billing Tos')  ,
				'label'=>_('Billing Address')
			),
			
			
			

		)
	),
	array(
		'label'=>_('Delivery'),
		'show_title'=>true,
		'fields'=>array(
			
            array(
				'class'=>'address',
				'id'=>'Order_Ship_To_Address',
				'value'=>$order->get('Order XHTML Ship Tos')  ,
				'label'=>_('Delivery Address')
			),
			
			
			

		)
	),
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('edit_object.tpl');

?>