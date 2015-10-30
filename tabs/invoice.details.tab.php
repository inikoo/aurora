<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:27 October 2015 at 17:25:16 CET, Train Napoles-Florence, Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/


$invoice=$state['_object'];

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'Invoice_Key',
				'value'=>$invoice->id  ,
				'label'=>_('Id')
			),
            array(
				'class'=>'locked',
				'id'=>'Invoice_Public_ID',
				'value'=>$invoice->get('Invoice Public ID')  ,
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
				'value'=>$invoice->get('Invoice Customer Name').' (<span class="id">'.sprintf("%05d",$invoice->get('Invoice Customer Key')).'</span>)',
				'label'=>_('Customer')
			),
			 array(
				'id'=>'Invoice_Customer_Fiscal_Name',
				'value'=>$invoice->get('Invoice Customer Fiscal Name')  ,
				'label'=>_('Fiscal name')
			),array(
				'id'=>'Invoice_Customer_Contact_Name',
				'value'=>$invoice->get('Invoice Customer Contact Name')  ,
				'label'=>_('Contact name')
			),array(
				'id'=>'Invoice_Telephone',
				'value'=>$invoice->get('Invoice Telephone')  ,
				'label'=>_('Contact telephone')
			),array(
				'id'=>'Invoice_Email',
				'value'=>$invoice->get('Invoice Email')  ,
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
				'id'=>'Invoice_Billing_Address',
				'value'=>$invoice->get('Invoice XHTML Billing Tos')  ,
				'label'=>_('Billing Address')
			),
			
			
			

		)
	),
	
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>