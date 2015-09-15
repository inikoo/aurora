<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created:13 September 2015 16:58:29 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

$customer=new Customer($state['key']);

if ($customer->data['Customer Type']=='Company') {

}

$company_field=array();

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'Customer_Key',
				'value'=>$customer->get_formated_id()  ,
				'label'=>_('Id')
			),

			array(
				'class'=>'string',
				'id'=>'Customer_Registration_Number',
				'value'=>$customer->get('Customer Registration Number'),
				'label'=>_('Registration number')
			),
			array(
				'class'=>'string',
				'id'=>'Customer_Tax_Number',
				'value'=>$customer->get('Customer Tax Number'),
				'label'=>_('Tax number')
			),

		)
	),
	array(
		'label'=>_('Contact'),
		'show_title'=>false,
		'fields'=>array(
			array(
				'class'=>'string',
				'id'=>'Customer_Name',
				'value'=>($customer->data['Customer Type']=='Company'?$customer->get('Customer Name'):'')  ,
				'label'=>_('Company name')
			),

			array(
				'class'=>'string',
				'id'=>'Customer_Main_Contact_Name',
				'value'=>$customer->get('Customer Main Contact Name'),
				'label'=>_('Contact name')
			),
			array(
				'class'=>'string',
				'id'=>'Customer_Main_Plain_Email',
				'value'=>$customer->get('Customer Main XHTML Email'),
				'label'=>_('Email')
			),
			array(
				'class'=>'string',
				'id'=>'Customer_Main_Plain_Telephone',
				'value'=>$customer->get('Customer Main Plain Telephone'),
				'label'=>_('Phone')
			),
			array(
				'class'=>'string',
				'id'=>'Customer_Main_Plain_Mobile',
				'value'=>$customer->get('Customer Main Plain Mobile'),
				'label'=>_('Mobile')
			),
			array(
				'class'=>'string',
				'id'=>'Customer_Main_Plain_FAX',
				'value'=>$customer->get('Customer Main Plain FAX'),
				'label'=>_('FAX')
			),
			array(
				'class'=>'address',
				'id'=>'Customer_Main_Plain_Adresss',
				'value'=>$customer->get('Customer Main XHTML Address'),
				'label'=>_('Address')
			)
		)
	),
	array(
		'label'=>_('Billing'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'address',
				'id'=>'Billing_Library',
				'value'=>( $customer->get('Customer Billing Address Link')=='Contact'?_('Same as contact address') : $customer->get('Customer XHTML Billing Address') ) ,
				'label'=>_('Billing Address')
			),

		)
	),
	array(
		'label'=>_('Delivery'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'address_library',
				'id'=>'Delivery_Library',
				'value'=>( $customer->get('Customer Delivery Address Link')=='Contact'?_('Same as contact address') : $customer->get('Customer XHTML Delivery Address') ) ,
				'label'=>_('Billing Address')
			),

		)
	),
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
