<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 11 April 2016 at 12:53:47 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once 'utils/static_data.php';

if (isset($options['new']) and  $options['new'] ) {
	$new=true;
}else {
	$new=false;
}

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
		'label'=>_('Code, name'),
		'show_title'=>true,
		'fields'=>array(
			array(

				'id'=>'Supplier_Code',
				'edit'=>($edit?'string':''),
				'value'=>$object->get('Supplier Code'),
				'label'=>ucfirst($object->get_field_label('Code')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'invalid_msg'=>get_invalid_message('string'),
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Company_Name',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Supplier Company Name')),
				'formatted_value'=>$object->get('Company Name'),
				'label'=>ucfirst($object->get_field_label('Supplier Company Name')),
				'required'=>false,
				'type'=>'value'
			),

			array(

				'id'=>'Supplier_Main_Contact_Name',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Supplier Main Contact Name')),
				'formatted_value'=>$object->get('Main Contact Name'),
				'label'=>ucfirst($object->get_field_label('Supplier Main Contact Name')),
				'required'=>true,
				'type'=>'value'
			),

		)
	),
	array(
		'label'=>_('Email'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Main_Plain_Email',
				'edit'=>($edit?'email':''),

				'value'=>$object->get('Supplier Main Plain Email'),
				'formatted_value'=>$object->get('Main Plain Email'),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'label'=>ucfirst($object->get_field_label('Supplier Main Plain Email')),
				'invalid_msg'=>get_invalid_message('email'),
				'required'=>false,
				'type'=>'value'
			), array(
				'id'=>'new_email',
				'render'=>false,
				'edit'=>'new_email',
				'value'=>'',
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'formatted_value'=>'',
				'label'=>ucfirst($object->get_field_label('Supplier Other Email')),
				'invalid_msg'=>get_invalid_message('email'),

				'required'=>false,
				'type'=>'ignore'
			),

			array(
				'id'=>'Supplier_Other_Email',
				'render'=>false,
				'edit'=>($edit?'email':''),

				'value'=>'',
				'formatted_value'=>'',
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'label'=>ucfirst($object->get_field_label('Supplier Other Email')).' <i onClick="set_this_as_main(this)" title="'._('Set as main email').'" class="fa fa-star-o very_discret button"></i>',
				'invalid_msg'=>get_invalid_message('email'),
				'required'=>false,
				'type'=>'value'
			),

			array(
				'render'=>($object->get('Supplier Main Plain Email')==''?false:true),
				'id'=>'show_new_email',
				'class'=>'new',
				'value'=>'',
				'label'=>_('Add email').' <i class="fa fa-plus new_button button"></i>',
				'reference'=>'',
				'type'=>'value'
			),

		)
	),

	array(
		'label'=>_('Telephones'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Main_Plain_Mobile',
				'edit'=>($edit?'telephone':''),
				'mobile'=>true,
				'value'=>$object->get('Supplier Main Plain Mobile'),
				'formatted_value'=>$object->get('Main Plain Mobile'),
				'label'=>ucfirst($object->get_field_label('Supplier Main Plain Mobile')). ($object->get('Supplier Main Plain Mobile')!=''?($object->get('Supplier Preferred Contact Number')=='Mobile'?'':' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>'):'')    ,
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
				'type'=>'value'
			),
			array(

				'id'=>'Supplier_Main_Plain_Telephone',
				'edit'=>($edit?'telephone':''),

				'value'=>$object->get('Supplier Main Plain Telephone'),
				'formatted_value'=>$object->get('Main Plain Telephone'),
				'label'=>ucfirst($object->get_field_label('Supplier Main Plain Telephone')).($object->get('Supplier Main Plain Telephone')!=''?($object->get('Supplier Preferred Contact Number')=='Telephone'?'':' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>'):'')    ,
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
				'type'=>'value'

			), array(
				'id'=>'new_telephone',
				'render'=>false,
				'edit'=>'new_telephone',
				'value'=>'',
				'formatted_value'=>'',
				'label'=>ucfirst($object->get_field_label('Supplier Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false,
				'type'=>'ignore'
			),

			array(
				'id'=>'Supplier_Other_Telephone',
				'render'=>false,
				'edit'=>($edit?'telephone':''),
				'clone_template'=>true,
				'value'=>'',
				'formatted_value'=>'',
				'label'=>ucfirst($object->get_field_label('Supplier Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false,
				'type'=>'ignore'
			),

			array(
				'render'=>($object->get('Supplier Main Plain Telephone')==''?false:true),
				'id'=>'show_new_telephone',
				'class'=>'new',
				'value'=>'',
				'label'=>_('Add telephone').' <i class="fa fa-plus new_button button"></i>',
				'required'=>false,
				'reference'=>'',
				'type'=>'ignore'
			),

			array(
				'id'=>'Supplier_Main_Plain_FAX',
				'edit'=>($edit?'telephone':''),

				'value'=>$object->get('Supplier Main Plain FAX'),
				'formatted_value'=>$object->get('Main Plain FAX'),
				'label'=>ucfirst($object->get_field_label('Supplier Main Plain FAX')),
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
				'type'=>'value'
			),

		)
	),

	array(
		'label'=>_('Address'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Contact_Address',
				'edit'=>($edit?'address':''),
				'countries'=>get_countries($db),

				'value'=>htmlspecialchars($object->get('Supplier Contact Address')),
				'formatted_value'=>$object->get('Contact Address'),
				'label'=>ucfirst($object->get_field_label('Supplier Contact Address')),
				'invalid_msg'=>get_invalid_message('address'),
				'required'=>false,
				'type'=>'value'
			),




		)
	),

	array(
		'label'=>_("Supplier's parts defaults"),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Average_Delivery_Days',
				'edit'=>'mediumint_unsigned',
				'value'=>$object->get('Supplier Average Delivery Days'),
				'formatted_value'=>$object->get('Average Delivery Days'),
				'label'=>ucfirst($object->get_field_label('Supplier Average Delivery Days')),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Default_Currency_Code',
				'edit'=>($edit?'country_select':''),
				'options'=>get_currencies($db),
				'scope'=>'currencies',
				'value'=>($new?$account->get('Account Currency'):$object->get('Supplier Default Currency Code')),
				'formatted_value'=>$object->get('Default Currency'),
				'label'=>ucfirst($object->get_field_label('Supplier Default Currency Code')),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Products_Origin_Country_Code',
				'edit'=>($edit?'country_select':''),
				'options'=>get_countries($db),
				'scope'=>'countries',
				'value'=> ($new?$account->get('Account Country Code'):htmlspecialchars($object->get('Supplier Products Origin Country Code'))),
				'formatted_value'=>($new?$account->get('Account Country Code'):$object->get('Products Origin Country Code')),
				'stripped_formatted_value'=>($new?$account->get('Account Country Code'):$object->get('Products Origin Country Code')),
				'label'=>ucfirst($object->get_field_label('Part Origin Country Code')),
				'required'=>false,
				'type'=>'value',

			),

		)
	),

	array(
		'label'=>_('Purchase order settings'),
		'show_title'=>false,
		'fields'=>array(


			array(
				'id'=>'Supplier_Default_Incoterm',
				'edit'=>($edit?'option':''),

				'options'=>$options_incoterms,
				'value'=>$object->get('Supplier Default Incoterm'),
				'formatted_value'=>$object->get('Default Incoterm'),
				'label'=>ucfirst($object->get_field_label('Supplier Default Incoterm')),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Default_Port_of_Export',
				'edit'=>($edit?'string':''),

				'value'=>$object->get('Supplier Default Port of Export'),
				'formatted_value'=>$object->get('Default Port of Export'),
				'label'=>ucfirst($object->get_field_label('Supplier Default Port of Export')),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Default_Port_of_Import',
				'edit'=>($edit?'string':''),

				'value'=>$object->get('Supplier Default Port of Import'),
				'formatted_value'=>$object->get('Default Port of Import'),
				'label'=>ucfirst($object->get_field_label('Supplier Default Port of Import')),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Default_PO_Terms_and_Conditions',
				'edit'=>($edit?'textarea':''),

				'value'=>$object->get('Supplier Default PO Terms and Conditions'),
				'formatted_value'=>$object->get('Default PO Terms and Conditions'),
				'label'=>ucfirst($object->get_field_label('Supplier Default PO Terms and Conditions')),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Show_Warehouse_TC_in_PO',
				'edit'=>($edit?'option':''),

				'options'=>$options_yn,
				'value'=>($new?'Yes':$object->get('Supplier Show Warehouse TC in PO')),
				'formatted_value'=>($new?_('Yes'):$object->get('Show Warehouse TC in PO')),
				'label'=>ucfirst($object->get_field_label('Supplier Show Warehouse TC in PO')),
				'required'=>false,
				'type'=>'value'
			),

		)
	),


);



if (!$new) {

	if ($object->get('Supplier User Key')) {


		$object_fields[]=array(
			'label'=>_('System user').' <i  onClick="change_view(\'account/user/'.$object->get('Supplier User Key').'\')" class="fa fa-link link"></i>',
			'show_title'=>true,
			'class'=>'edit_fields',
			'fields'=>array(

				array(

					'id'=>'Supplier_User_Active',
					'edit'=>'option',
					'value'=>$object->get('Supplier User Active'),
					'formatted_value'=>$object->get('User Active'),
					'options'=>$options_yn,
					'label'=>ucfirst($object->get_field_label('Supplier Active')),
				),

				array(

					'id'=>'Supplier_User_Handle',
					'edit'=>'handle',
					'value'=>$object->get('Supplier User Handle'),
					'formatted_value'=>$object->get('User Handle'),
					'label'=>ucfirst($object->get_field_label('Supplier User Handle')),
					'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates', 'parent'=>'account', 'parent_key'=>1,'actual_field'=>'User Handle', 'object'=>'User', 'key'=>$object->id)),
					'invalid_msg'=>get_invalid_message('handle'),
				),

				array(
					'render'=>($object->get('Supplier User Active')=='Yes'?true:false),

					'id'=>'Supplier_User_Password',
					'edit'=>'password',
					'value'=>'',
					'formatted_value'=>'******',
					'label'=>ucfirst($object->get_field_label('Supplier User Password')),
					'invalid_msg'=>get_invalid_message('password'),
				),
				array(
					'render'=>($object->get('Supplier User Active')=='Yes'?true:false),

					'id'=>'Supplier_User_PIN',
					'edit'=>'pin',
					'value'=>'',
					'formatted_value'=>'****',
					'label'=>ucfirst($object->get_field_label('Supplier User PIN')),
					'invalid_msg'=>get_invalid_message('pin'),
				),



			)
		);

	}
	else {
		$object_fields[]=array(
			'label'=>_('System user'),
			'show_title'=>true,
			'class'=>'edit_fields',
			'fields'=>array(
				array(

					'id'=>'new_user',
					'class'=>'new',
					'value'=>'',
					'label'=>_('Set up system user').' <i class="fa fa-plus new_button link"></i>',
					'reference'=>'supplier/'.$object->id.'/user/new'
				),

			)
		);

	}


	$operations=array(
		'label'=>_('Operations'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(

				'id'=>'delete_supplier',
				'class'=>'operation',
				'value'=>'',
				'label'=>'<i class="fa fa-fw fa-lock button" onClick="toggle_unlock_delete_object(this)" style="margin-right:20px"></i> <span onClick="delete_object(this)" class="delete_object disabled">'._("Delete supplier & supplier's parts").' <i class="fa fa-trash new_button link"></i></span>',
				'reference'=>'',
				'type'=>'operation'
			),

		)

	);

	$object_fields[]=$operations;
}else {

	$object_fields[]=array(
		'label'=>_('System user'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(


			array(

				'id'=>'add_new_user',
				'class'=>'',
				'value'=>'',
				'label'=>_('Set up system user').' <i onClick="show_user_fields()" class="fa fa-plus new_button link"></i>',
				'required'=>false,
				'type'=>'util'
			),

			array(
				'render'=>false,
				'id'=>'dont_add_new_user',
				'class'=>'',
				'value'=>'',
				'label'=>_("Don't set up system user").' <i onClick="hide_user_fields()" class="fa fa-minus new_button link"></i>',
				'required'=>false,
				'type'=>'util'
			),


			array(
				'render'=>false,
				'id'=>'Supplier_User_Active',
				'edit'=>($edit?'option':''),

				'options'=>$options_yn,
				'value'=>'Yes',
				'formatted_value'=>_('Yes'),
				'label'=>ucfirst($object->get_field_label('Supplier User Active')),
				'type'=>'user_value',
				'hidden'=>true
			),
			array(
				'render'=>false,
				'id'=>'Supplier_User_Handle',
				'edit'=>'handle',
				'value'=>$object->get('Supplier User Handle'),
				'formatted_value'=>$object->get('User Handle'),
				'label'=>ucfirst($object->get_field_label('Supplier User Handle')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'invalid_msg'=>get_invalid_message('handle'),
				'type'=>'user_value',
				'required'=>false,

			),



			array(
				'render'=>false,

				'id'=>'Supplier_User_Password',
				'edit'=>'password',
				'value'=>'',
				'formatted_value'=>'******',
				'label'=>ucfirst($object->get_field_label('Supplier User Password')),
				'invalid_msg'=>get_invalid_message('password'),
				'type'=>'user_value',
				'required'=>false,


			),
			array(
				'render'=>false,
				'id'=>'Supplier_PIN',
				'edit'=>'pin',
				'value'=>'',
				'formatted_value'=>'****',
				'label'=>ucfirst($object->get_field_label('Supplier PIN')),
				'invalid_msg'=>get_invalid_message('pin'),
				'type'=>'user_value',
				'required'=>false,

			),



		)
	);
}












$other_emails=$object->get_other_emails_data();
if (count($other_emails)>0) {
	$other_emails_fields=array();
	foreach ($other_emails as $other_email_data_key=>$other_email_data) {
		$other_emails_fields[]=array(
			'id'=>'Supplier_Other_Email_'.$other_email_data_key,
			'edit'=>($edit?'email':''),

			'value'=>$other_email_data['email'],
			'formatted_value'=>$other_email_data['email'],
			'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
			'label'=>ucfirst($object->get_field_label('Supplier Other Email')).' <i onClick="set_this_as_main(this)" title="'._('Set as main email').'" class="fa fa-star-o very_discret button"></i>',
			'required'=>false
		);
	}
	array_splice( $object_fields[1]['fields'], 1, 0, $other_emails_fields);
}

$other_telephones=$object->get_other_telephones_data();
if (count($other_telephones)>0) {
	$other_telephones_fields=array();
	foreach ($other_telephones as $other_telephone_data_key=>$other_telephone_data) {
		$other_telephones_fields[]=array(
			'id'=>'Supplier_Other_Telephone_'.$other_telephone_data_key,
			'edit'=>($edit?'telephone':''),

			'value'=>$other_telephone_data['telephone'],
			'formatted_value'=>$other_telephone_data['formatted_telephone'],
			'label'=>ucfirst($object->get_field_label('Supplier Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
			'required'=>false
		);
	}
	array_splice( $object_fields[2]['fields'], 2, 0, $other_telephones_fields);
}




?>
