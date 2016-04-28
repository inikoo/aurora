<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 April 2016 at 18:43:07 GMT+8. Lovina, Bali, Indonesia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

include_once('utils/static_data.php');

if (isset($options['new']) and  $options['new'] ) {
	$new=true;
}else {
	$new=false;
}

$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);






$agent_fields=array(

	
	array(
		'label'=>_('Code, name'),
		'show_title'=>true,
		'fields'=>array(
			array(

				'id'=>'Agent_Code',
				'edit'=>($edit?'string':''),
				'value'=>$object->get('Agent Code'),
				'label'=>ucfirst($object->get_field_label('Code')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'invalid_msg'=>get_invalid_message('string'),
				'type'=>'value'
			),
			array(
				'id'=>'Agent_Company_Name',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Agent Company Name')),
				'formatted_value'=>$object->get('Company Name'),
				'label'=>ucfirst($object->get_field_label('Agent Company Name')),
				'required'=>false,
				'type'=>'value'
			),

			array(

				'id'=>'Agent_Main_Contact_Name',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Agent Main Contact Name')),
				'formatted_value'=>$object->get('Main Contact Name'),
				'label'=>ucfirst($object->get_field_label('Agent Main Contact Name')),
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
				'id'=>'Agent_Main_Plain_Email',
				'edit'=>($edit?'email':''),

				'value'=>$object->get('Agent Main Plain Email'),
				'formatted_value'=>$object->get('Main Plain Email'),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'label'=>ucfirst($object->get_field_label('Agent Main Plain Email')),
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
				'label'=>ucfirst($object->get_field_label('Agent Other Email')),
				'invalid_msg'=>get_invalid_message('email'),

				'required'=>false,
				'type'=>'ignore'
			),

			array(
				'id'=>'Agent_Other_Email',
				'render'=>false,
				'edit'=>($edit?'email':''),

				'value'=>'',
				'formatted_value'=>'',
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'label'=>ucfirst($object->get_field_label('Agent Other Email')).' <i onClick="set_this_as_main(this)" title="'._('Set as main email').'" class="fa fa-star-o very_discret button"></i>',
				'invalid_msg'=>get_invalid_message('email'),
				'required'=>false,
				'type'=>'value'
			),

			array(
				'render'=>($object->get('Agent Main Plain Email')==''?false:true),
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
				'id'=>'Agent_Main_Plain_Mobile',
				'edit'=>($edit?'telephone':''),
				'mobile'=>true,
				'value'=>$object->get('Agent Main Plain Mobile'),
				'formatted_value'=>$object->get('Main Plain Mobile'),
				'label'=>ucfirst($object->get_field_label('Agent Main Plain Mobile')). ($object->get('Agent Main Plain Mobile')!=''?($object->get('Agent Preferred Contact Number')=='Mobile'?'':' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>'):'')    ,
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
				'type'=>'value'
			),
			array(



				'id'=>'Agent_Main_Plain_Telephone',
				'edit'=>($edit?'telephone':''),

				'value'=>$object->get('Agent Main Plain Telephone'),
				'formatted_value'=>$object->get('Main Plain Telephone'),
				'label'=>ucfirst($object->get_field_label('Agent Main Plain Telephone')).($object->get('Agent Main Plain Telephone')!=''?($object->get('Agent Preferred Contact Number')=='Telephone'?'':' <i onClick="set_this_as_main(this)" title="'._('Set as main contact number').'" class="fa fa-star-o discret button"></i>'):'')    ,
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
				'type'=>'value'

			), array(
				'id'=>'new_telephone',
				'render'=>false,
				'edit'=>'new_telephone',
				'value'=>'',
				'formatted_value'=>'',
				'label'=>ucfirst($object->get_field_label('Agent Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false,
				'type'=>'ignore'
			),

			array(
				'id'=>'Agent_Other_Telephone',
				'render'=>false,
				'edit'=>($edit?'telephone':''),
                 'clone_template'=>true,
				'value'=>'',
				'formatted_value'=>'',
				'label'=>ucfirst($object->get_field_label('Agent Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
				'required'=>false,
				'type'=>'ignore'
			),

			array(
				'render'=>($object->get('Agent Main Plain Telephone')==''?false:true),
				'id'=>'show_new_telephone',
				'class'=>'new',
				'value'=>'',
				'label'=>_('Add telephone').' <i class="fa fa-plus new_button button"></i>',
				'required'=>false,
				'reference'=>'',
				'type'=>'ignore'
			),

			array(
				'id'=>'Agent_Main_Plain_FAX',
				'edit'=>($edit?'telephone':''),

				'value'=>$object->get('Agent Main Plain FAX'),
				'formatted_value'=>$object->get('Main Plain FAX'),
				'label'=>ucfirst($object->get_field_label('Agent Main Plain FAX')),
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
				'id'=>'Agent_Contact_Address',
				'edit'=>($edit?'address':''),
				'countries'=>get_countries($db),

				'value'=>htmlspecialchars($object->get('Agent Contact Address')),
				'formatted_value'=>$object->get('Contact Address'),
				'label'=>ucfirst($object->get_field_label('Agent Contact Address')),
				'invalid_msg'=>get_invalid_message('address'),
				'required'=>false,
				'type'=>'value'
			),




		)
	),


	
	
	

);



if (!$new) {
	$operations=array(
		'label'=>_('Operations'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(
			array(

				'id'=>'delete_agent',
				'class'=>'new',
				'value'=>'',
				'label'=>'<i class="fa fa-lock button" style="margin-right:20px"></i> <span class="disabled">'._('Delete agent').' <i class="fa fa-trash new_button link"></i></span>',
				'reference'=>'',
				'type'=>'ignore'
			),

		)

	);
	
	$agent_fields[]=$operations;
}



$other_emails=$object->get_other_emails_data();
if (count($other_emails)>0) {
	$other_emails_fields=array();
	foreach ($other_emails as $other_email_data_key=>$other_email_data) {
		$other_emails_fields[]=array(
			'id'=>'Agent_Other_Email_'.$other_email_data_key,
			'edit'=>($edit?'email':''),

			'value'=>$other_email_data['email'],
			'formatted_value'=>$other_email_data['email'],
			'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
			'label'=>ucfirst($object->get_field_label('Agent Other Email')).' <i onClick="set_this_as_main(this)" title="'._('Set as main email').'" class="fa fa-star-o very_discret button"></i>',
			'required'=>false
		);
	}
	array_splice( $agent_fields[1]['fields'], 1, 0, $other_emails_fields);
}

$other_telephones=$object->get_other_telephones_data();
if (count($other_telephones)>0) {
	$other_telephones_fields=array();
	foreach ($other_telephones as $other_telephone_data_key=>$other_telephone_data) {
		$other_telephones_fields[]=array(
			'id'=>'Agent_Other_Telephone_'.$other_telephone_data_key,
			'edit'=>($edit?'telephone':''),

			'value'=>$other_telephone_data['telephone'],
			'formatted_value'=>$other_telephone_data['formatted_telephone'],
			'label'=>ucfirst($object->get_field_label('Agent Other Telephone')).' <i onClick="set_this_as_main(this)" title="'._('Set as main telephone').'" class="fa fa-star-o very_discret button"></i>',
			'required'=>false
		);
	}
	array_splice( $agent_fields[2]['fields'], 2, 0, $other_telephones_fields);
}




?>
