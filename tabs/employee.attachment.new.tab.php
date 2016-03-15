<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 3 December 2015 at 20:48:17 GMT Sheffied UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';
include_once 'class.Staff.php';

$attachment=$state['_object'];
$attachment->set_subject('staff');

$options_Attachment_Subject_Type=array(
	'CV'=>_('Curriculum vitae'),
	'Contract'=>_('Employment contract'),
	'Other'=>_('Other'),

);
$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);
asort($options_Attachment_Subject_Type);
asort($options_yn);

$object_fields=array(
	array(
		'label'=>_('Description'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(



			array(
				'id'=>'Attachment_Subject_Type',
				'edit'=>'option',
				'value'=>'Other',
				'formatted_value'=>_('Other'),
				'options'=>$options_Attachment_Subject_Type,
				'label'=>ucfirst($attachment->get_field_label('Attachment Subject Type')),
				'required'=>true,

				'type'=>'value'
			),
			array(

				'id'=>'Attachment_Caption',
				'edit'=>'string',
				'value'=>'',
				'formatted_value'=>'',

				'label'=>ucfirst($attachment->get_field_label('Attachment Caption')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'type'=>'value'

			),

		)
	),

	array(
		'label'=>_('Restrictions'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(

			array(

				'id'=>'Attachment_Public',
				'edit'=>'option',
				'value'=>'No',
				'formatted_value'=>_('No'),
				'options'=>$options_yn,
				'label'=>ucfirst($attachment->get_field_label('Attachment Public')),
				'required'=>true,

				'type'=>'value'

			)

		)
	),

	array(
		'label'=>_('Attachment'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(

			array(

				'id'=>'Attachment_File',
				'edit'=>'attachment',
				'value'=>'',
				'formatted_value'=>'',
				'label'=>ucfirst($attachment->get_field_label('Attachment File')),
				'required'=>true,

				'type'=>'value'

			)

		)
	),

);



$smarty->assign('state', $state);
$smarty->assign('object', $attachment);

$smarty->assign('object_name', preg_replace('/ /', '_', $attachment->get_object_name()));


$smarty->assign('form_type', 'upload_attachment');

$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('new_object.tpl');

?>
