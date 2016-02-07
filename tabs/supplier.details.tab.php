<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 5 February 2016 at 20:00:27 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/



include_once 'utils/country_functions.php';

include_once 'utils/invalid_messages.php';



$supplier=$state['_object'];


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
				'id'=>'Supplier_Name',
				'edit'=>'string',
				'value'=>htmlspecialchars($supplier->get('Supplier Name')),
				'formatted_value'=>$supplier->get('Name'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Name')),
				'required'=>false
			),

			array(

				'id'=>'Supplier_Main_Contact_Name',
				'edit'=>'string',
				'value'=>htmlspecialchars($supplier->get('Supplier Main Contact Name')),
				'formatted_value'=>$supplier->get('Main Contact Name'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Contact Name')),
				'required'=>true
			),
			array(
				'id'=>'Supplier_Registration_Number',
				'edit'=>'string',
				'value'=>$supplier->get('Supplier Registration Number'),
				'formatted_value'=>$supplier->get('Registration Number'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Registration Number')),
				'required'=>false
			),
			array(
				'id'=>'Supplier_Tax_Number',
				'edit'=>'string',
				'value'=>$supplier->get('Supplier Tax Number'),
				'formatted_value'=>$supplier->get('Tax Number'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Tax Number')),
				'required'=>false

			),
			array(
				'render'=>($supplier->get('Supplier Tax Number')==''?false:true),
				'id'=>'Supplier_Tax_Number_Valid',
				'edit'=>'option',
				'options'=>$options_valid_tax_number,
				'value'=>$supplier->get('Supplier Tax Number Valid'),
				'formatted_value'=>$supplier->get('Tax Number Valid'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Tax Number Valid')),
			),

		)
	),
	array(
		'label'=>_('Email'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Main_Plain_Email',
				'edit'=>'email',
				'value'=>$supplier->get('Supplier Main Plain Email'),
				'formatted_value'=>$supplier->get('Main Plain Email'),
				'server_validation'=>'check_for_duplicates',
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Plain Email')),
				'invalid_msg'=>get_invalid_message('email'),
				'required'=>true
			), array(
				'id'=>'new_email',
				'render'=>false,
				'edit'=>'new_email',
				'value'=>'',
				'server_validation'=>'check_for_duplicates',
				'formatted_value'=>'',
				'label'=>ucfirst($supplier->get_field_label('Supplier Other Email')),
				'invalid_msg'=>get_invalid_message('email'),

				'required'=>false
			),

			array(
				'id'=>'Supplier_Other_Email',
				'render'=>false,
				'edit'=>'email',
				'value'=>'',
				'formatted_value'=>'',
				'server_validation'=>'check_for_duplicates',
				'label'=>ucfirst($supplier->get_field_label('Supplier Other Email')).' <i onClick="set_this_as_main(this)" title="'._('Set as main email').'" class="fa fa-star-o very_discret button"></i>',
				'invalid_msg'=>get_invalid_message('email'),
				'required'=>false
			),

			array(
				'render'=>($supplier->get('Supplier Main Plain Email')==''?false:true),
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
				'id'=>'Supplier_Main_Plain_Mobile',
				'edit'=>'telephone',
				'value'=>$supplier->get('Supplier Main Plain Mobile'),
				'formatted_value'=>$supplier->get('Main Plain Mobile'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Plain Mobile')). ($supplier->get('Supplier Main Plain Mobile')!=''?($supplier->get('Supplier Preferred Contact Number')=='Mobile'?'':' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>'):'')    ,
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false
			),
			array(



				'id'=>'Supplier_Main_Plain_Telephone',
				'edit'=>'telephone',
				'value'=>$supplier->get('Supplier Main Plain Telephone'),
				'formatted_value'=>$supplier->get('Main Plain Telephone'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Plain Telephone')).($supplier->get('Supplier Main Plain Telephone')!=''?($supplier->get('Supplier Preferred Contact Number')=='Telephone'?'':' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>'):'')    ,
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false

			), array(
				'id'=>'new_telephone',
				'render'=>false,
				'edit'=>'new_telephone',
				'value'=>'',
				'formatted_value'=>'',
				'label'=>ucfirst($supplier->get_field_label('Supplier Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false
			),

			array(
				'id'=>'Supplier_Other_Telephone',
				'render'=>false,
				'edit'=>'telephone',
				'value'=>'',
				'formatted_value'=>'',
				'label'=>ucfirst($supplier->get_field_label('Supplier Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false
			),

			array(
				'render'=>($supplier->get('Supplier Main Plain Telephone')==''?false:true),
				'id'=>'show_new_telephone',
				'class'=>'new',
				'value'=>'',
				'label'=>_('Add telephone').' <i class="fa fa-plus new_button button"></i>',
				'required'=>false,
				'reference'=>''
			),

			array(
				'id'=>'Supplier_Main_Plain_FAX',
				'edit'=>'telephone',
				'value'=>$supplier->get('Supplier Main Plain FAX'),
				'formatted_value'=>$supplier->get('Main Plain FAX'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Plain FAX')),
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false
			),

		)
	),

	array(
		'label'=>_('Address'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Contact_Address',
				'edit'=>'address',
				'value'=>htmlspecialchars($supplier->get('Supplier Contact Address')),
				'formatted_value'=>$supplier->get('Contact Address'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Contact Address')),
				'invalid_msg'=>get_invalid_message('address'),
				'required'=>false
			),


		

		)
	),

array(
		'label'=>_('Operations'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(

				'id'=>'delete_supplier',
				'class'=>'new',
				'value'=>'',
				'label'=>'<i class="fa fa-lock button" style="margin-right:20px"></i> <span class="disabled">'._('Delete supplier').' <i class="fa fa-trash new_button link"></i></span>',
				'reference'=>''
			),

		)
		
	),

);

$other_emails=$supplier->get_other_emails_data();
if (count($other_emails)>0) {
	$other_emails_fields=array();
	foreach ($other_emails as $other_email_data_key=>$other_email_data) {
		$other_emails_fields[]=array(
			'id'=>'Supplier_Other_Email_'.$other_email_data_key,
			'edit'=>'email',
			'value'=>$other_email_data['email'],
			'formatted_value'=>$other_email_data['email'],
			'server_validation'=>'check_for_duplicates',
			'label'=>ucfirst($supplier->get_field_label('Supplier Other Email')).' <i onClick="set_this_as_main(this)" title="'._('Set as main email').'" class="fa fa-star-o very_discret button"></i>',
			'required'=>false
		);
	}
	array_splice( $object_fields[1]['fields'], 1, 0, $other_emails_fields);
}

$other_telephones=$supplier->get_other_telephones_data();
if (count($other_telephones)>0) {
	$other_telephones_fields=array();
	foreach ($other_telephones as $other_telephone_data_key=>$other_telephone_data) {
		$other_telephones_fields[]=array(
			'id'=>'Supplier_Other_Telephone_'.$other_telephone_data_key,
			'edit'=>'telephone',
			'value'=>$other_telephone_data['telephone'],
			'formatted_value'=>$other_telephone_data['formatted_telephone'],
			'label'=>ucfirst($supplier->get_field_label('Supplier Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
			'required'=>false
		);
	}
	array_splice( $object_fields[2]['fields'], 2, 0, $other_telephones_fields);
}



$smarty->assign('default_country', $account->get('Account Country 2 Alpha Code'));
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries($account->get('Account Country 2 Alpha Code'))).'"');

$smarty->assign('object', $state['_object']);
$smarty->assign('key', $state['key']);

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);
//$smarty->assign('js_code', file_get_contents('js/supplier.details.js'));

//print_r($supplier->get('Supplier Contact Address'));

$html=$smarty->fetch('edit_object.tpl');

?>
