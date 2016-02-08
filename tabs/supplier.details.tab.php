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

$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);

$options_incoterms=array();
$sql="select `Incoterm Transport Type`,`Incoterm Name`,`Incoterm Code` from kbase.`Incoterm Dimension` order by `Incoterm Code` ";

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		if ($row['Incoterm Transport Type']=='Sea') {
			$transport_method=sprintf('<img style="height:12px" src="art/icons/transport_sea.png" alt="sea" title="%s">', _('Maritime and inland waterways'));
		}else {
			$transport_method=sprintf('<img  style="height:12px" src="art/icons/transport_land.png" alt="land" title="%s"> <img style="height:12px" src="art/icons/transport_sea.png" alt="sea" title="%s"> <img  style="height:12px" src="art/icons/transport_air.png" alt="air" title="%s">',
				_('Land'),
				_('Maritime and inland waterway'),
				_('Air')
			);

		}
		$options_incoterms[$row['Incoterm Code']]=sprintf("%s %s", $row['Incoterm Code'], $row['Incoterm Name']);
	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}

$options_currencies=array();
$sql="select `Currency Code`,`Currency Name`,`Currency Symbol` from kbase.`Currency Dimension` order by `Currency Code`";

if ($result=$db->query($sql)) {
	foreach ($result as $row) {

		$options_currencies[$row['Currency Code']]=sprintf("%s %s (%s)", $row['Currency Code'], $row['Currency Name'], $row['Currency Symbol']);
	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}




asort($options_yn);


$company_field=array();

$object_fields=array(
	array(
		'label'=>_('Name, Ids'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Supplier_Company_Name',
				'edit'=>'string',
				'value'=>htmlspecialchars($supplier->get('Supplier Company Name')),
				'formatted_value'=>$supplier->get('Company Name'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Company Name')),
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
		'label'=>_('Purchase, reordering'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Average_Delivery_Days',
				'edit'=>'mediumint_unsigned',
				'value'=>$supplier->get('Supplier Average Delivery Days'),
				'formatted_value'=>$supplier->get('Average Delivery Days'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Average Delivery Days')),
				'required'=>false
			),
			array(
				'id'=>'Supplier_Default_Currency',
				'edit'=>'option',
				'options'=>$options_currencies,
				'value'=>$supplier->get('Supplier Default Currency'),
				'formatted_value'=>$supplier->get('Default Currency'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Default Currency')),
				'required'=>false
			),


		)
	),
	array(
		'label'=>_('Purchase order settings'),
		'show_title'=>false,
		'fields'=>array(


			array(
				'id'=>'Supplier_Default_Incoterm',
				'edit'=>'option',
				'options'=>$options_incoterms,
				'value'=>$supplier->get('Supplier Default Incoterm'),
				'formatted_value'=>$supplier->get('Default Incoterm'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Default Incoterm')),
				'required'=>false
			),
			array(
				'id'=>'Supplier_Default_Port_of_Export',
				'edit'=>'string',
				'value'=>$supplier->get('Supplier Default Port of Export'),
				'formatted_value'=>$supplier->get('Default Port of Export'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Default Port of Export')),
				'required'=>false
			),
			array(
				'id'=>'Supplier_Default_Port_of_Import',
				'edit'=>'string',
				'value'=>$supplier->get('Supplier Default Port of Import'),
				'formatted_value'=>$supplier->get('Default Port of Import'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Default Port of Import')),
				'required'=>false
			),
			array(
				'id'=>'Supplier_Default_PO_Terms_and_Conditions',
				'edit'=>'textarea',
				'value'=>$supplier->get('Supplier Default PO Terms and Conditions'),
				'formatted_value'=>$supplier->get('Default PO Terms and Conditions'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Default PO Terms and Conditions')),
				'required'=>false
			),
			array(
				'id'=>'Supplier_Show_Warehouse_TC_in_PO',
				'edit'=>'option',
				'options'=>$options_yn,
				'value'=>$supplier->get('Supplier Show Warehouse TC in PO'),
				'formatted_value'=>$supplier->get('Show Warehouse TC in PO'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Show Warehouse TC in PO')),
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
