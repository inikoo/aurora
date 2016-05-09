<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 May 2016 at 15:16:01 GMT+8, Puchong, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

if (isset($options['new']) and  $options['new'] ) {
	$new=true;
}else {
	$new=false;
}



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Site_Code',
				'edit'=>($edit?'string':''),
				'value'=>$object->get('Site Code'),
				'label'=>ucfirst($object->get_field_label('Code')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'invalid_msg'=>get_invalid_message('string'),
			),
			array(
				'id'=>'Site_Name',
				'edit'=>($edit?'string':''),
				'value'=>$object->get('Site Name'),
				'label'=>ucfirst($object->get_field_label('Name')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'invalid_msg'=>get_invalid_message('string'),
			),

		)
	),
	array(
		'label'=>_('Contact'),
		'show_title'=>false,
		'fields'=>array(


			array(
				'class'=>'string',
				'id'=>'Site_Main_Contact_Name',
				'value'=>$object->get('Site Main Contact Name'),
				'label'=>_('Contact name')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Main_Plain_Email',
				'value'=>$object->get('Site Main XHTML Email'),
				'label'=>_('Email')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Main_Plain_Telephone',
				'value'=>$object->get('Site Main Plain Telephone'),
				'label'=>_('Phone')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Main_Plain_Mobile',
				'value'=>$object->get('Site Main Plain Mobile'),
				'label'=>_('Mobile')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Main_Plain_FAX',
				'value'=>$object->get('Site Main Plain FAX'),
				'label'=>_('FAX')
			),
			array(
				'class'=>'address',
				'id'=>'Site_Main_Plain_Adresss',
				'value'=>$object->get('Site Main XHTML Address'),
				'label'=>_('Address')
			)
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

				'id'=>'delete_website',
				'class'=>'new',
				'value'=>'',
				'label'=>'<i class="fa fa-lock button" style="margin-right:20px"></i> <span class="disabled">'._('Delete website').' <i class="fa fa-trash new_button link"></i></span>',
				'reference'=>'',
				'type'=>'ignore'
			),

		)

	);

	$agent_fields[]=$operations;
}





?>
