<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 16:58:29 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/



include_once 'utils/invalid_messages.php';



$customer=$state['_object'];

if ($customer->data['Customer Type']=='Company') {

}

$options_valid_tax_number=array(
	'Yes'=>_('Valid'), 'No'=>_('Not Valid'), 'Unknown'=>_('Unknown'), 'Auto'=>_('Check online'),
);


$company_field=array();

$object_fields=array(
	array(
		'label'=>_('Name, Ids'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Customer_Company_Name',
				'edit'=>'string',
				'value'=>$customer->get('Customer Company Name'),
				'formated_value'=>$customer->get('Company Name'),
				'label'=>ucfirst($customer->get_field_label('Customer Company Name')),
				'required'=>false
			),

			array(

				'id'=>'Customer_Main_Contact_Name',
				'edit'=>'string',
				'value'=>$customer->get('Customer Main Contact Name'),
				'formated_value'=>$customer->get('Main Contact Name'),
				'label'=>ucfirst($customer->get_field_label('Customer Main Contact Name')),
				'required'=>true
			),
			array(
				'id'=>'Customer_Registration_Number',
				'edit'=>'string',
				'value'=>$customer->get('Customer Registration Number'),
				'formated_value'=>$customer->get('Registration Number'),
				'label'=>ucfirst($customer->get_field_label('Customer Registration Number')),
				'required'=>false
			),
			array(
				'id'=>'Customer_Tax_Number',
				'edit'=>'string',
				'value'=>$customer->get('Customer Tax Number'),
				'formated_value'=>$customer->get('Tax Number'),
				'label'=>ucfirst($customer->get_field_label('Customer Tax Number')),
				'required'=>false

			),
			array(
				'render'=>($customer->get('Customer Tax Number')==''?false:true),
				'id'=>'Customer_Tax_Number_Valid',
				'edit'=>'option',
				'options'=>$options_valid_tax_number,
				'value'=>$customer->get('Customer Tax Number Valid'),
				'formated_value'=>$customer->get('Tax Number Valid'),
				'label'=>ucfirst($customer->get_field_label('Customer Tax Number Valid')),
			)

		)
	),
	array(
		'label'=>_('Email'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Customer_Main_Plain_Email',
				'edit'=>'email',
				'value'=>$customer->get('Customer Main Plain Email'),
				'formated_value'=>$customer->get('Main Plain Email'),
				'server_validation'=>'check_for_duplicates',
				'label'=>ucfirst($customer->get_field_label('Customer Main Plain Email')).' <i title="'._('Main email').'" class="fa fa-star discret"></i>',
				'required'=>false
			), array(
				'id'=>'new_email',
				'render'=>false,
				'edit'=>'new_email',
				'value'=>'',
				'server_validation'=>'check_for_duplicates',
				'formated_value'=>'',
				'label'=>ucfirst($customer->get_field_label('Customer Other Email')),
				'required'=>false
			),

			array(
				'id'=>'Customer_Other_Email',
				'render'=>false,
				'edit'=>'email',
				'value'=>'',
				'formated_value'=>'',
				'server_validation'=>'check_for_duplicates',
				'label'=>ucfirst($customer->get_field_label('Customer Other Email')).' <i onClick="set_this_as_main(this)" title="'._('Set as main email').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false
			),

			array(
				'render'=>($customer->get('Customer Main Plain Email')==''?false:true),
				'id'=>'show_new_email',
				'class'=>'new',
				'value'=>'',
				'label'=>_('Add email').' <i class="fa fa-plus new_button button"></i>',
				'reference'=>''
			),

		)
	),
	array(
		'label'=>_('Telephones'),
		'show_title'=>false,
		'fields'=>array(

			array(
					'id'=>'Customer_Main_Plain_Mobile',
				'edit'=>'telephone',
				'value'=>$customer->get('Customer Main Plain Mobile'),
				'formated_value'=>$customer->get('Main Plain Mobile'),
				'label'=>ucfirst($customer->get_field_label('Customer Main Plain Mobile')). ($customer->get('Customer Main Plain Mobile')!=''?($customer->get('Customer Preferred Contact Number')=='Mobile'?' <i title="'._('Main contact number').'" class="fa fa-star discret"></i>':' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>'):'')    ,
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false
			),
			array(



				'id'=>'Customer_Main_Plain_Telephone',
				'edit'=>'telephone',
				'value'=>$customer->get('Customer Main Plain Telephone'),
				'formated_value'=>$customer->get('Main Plain Telephone'),
				'label'=>ucfirst($customer->get_field_label('Customer Main Plain Telephone')).($customer->get('Customer Main Plain Telephone')!=''?($customer->get('Customer Preferred Contact Number')=='Telephone'?' <i title="'._('Main contact number').'" class="fa fa-star discret"></i>':' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>'):'')    ,
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false

			), array(
				'id'=>'new_telephone',
				'render'=>false,
				'edit'=>'new_telephone',
				'value'=>'',
				'formated_value'=>'',
				'label'=>ucfirst($customer->get_field_label('Customer Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false
			),

			array(
				'id'=>'Customer_Other_Telephone',
				'render'=>false,
				'edit'=>'telephone',
				'value'=>'',
				'formated_value'=>'',
				'label'=>ucfirst($customer->get_field_label('Customer Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false
			),

			array(
				'render'=>($customer->get('Customer Main Plain Telephone')==''?false:true),
				'id'=>'show_new_telephone',
				'class'=>'new',
				'value'=>'',
				'label'=>_('Add telephone').' <i class="fa fa-plus new_button button"></i>',
				'required'=>false,
				'reference'=>''
			),

			array(
					'id'=>'Customer_Main_Plain_FAX',
				'edit'=>'telephone',
				'value'=>$customer->get('Customer Main Plain FAX'),
				'formated_value'=>$customer->get('Main Plain FAX'),
				'label'=>ucfirst($customer->get_field_label('Customer Main Plain FAX')),
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false
			),

		)
	),
	array(
		'label'=>_('Contact Address'),
		'show_title'=>false,
		'fields'=>array(


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

$other_emails=$customer->get_other_emails_data();
if (count($other_emails)>0) {
	$other_emails_fields=array();
	foreach ($other_emails as $other_email_data_key=>$other_email_data) {
		$other_emails_fields[]=array(
			'id'=>'Customer_Other_Email_'.$other_email_data_key,
			'edit'=>'email',
			'value'=>$other_email_data['email'],
			'formated_value'=>$other_email_data['email'],
			'server_validation'=>'check_for_duplicates',
			'label'=>ucfirst($customer->get_field_label('Customer Other Email')).' <i onClick="set_this_as_main(this)" title="'._('Set as main email').'" class="fa fa-star-o very_discret button"></i>',
			'required'=>false
		);
	}
	array_splice( $object_fields[1]['fields'], 1, 0, $other_emails_fields);
}

$other_telephones=$customer->get_other_telephones_data();
if (count($other_telephones)>0) {
	$other_telephones_fields=array();
	foreach ($other_telephones as $other_telephone_data_key=>$other_telephone_data) {
		$other_telephones_fields[]=array(
			'id'=>'Customer_Other_Telephone_'.$other_telephone_data_key,
			'edit'=>'telephone',
			'value'=>$other_telephone_data['telephone'],
			'formated_value'=>$other_telephone_data['formated_telephone'],
			'label'=>ucfirst($customer->get_field_label('Customer Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
			'required'=>false
		);
	}
	array_splice( $object_fields[2]['fields'], 2, 0, $other_telephones_fields);
}



$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
$smarty->assign('js_code', file_get_contents('js/customer.details.js'));

$html=$smarty->fetch('object_fields.tpl');

?>
