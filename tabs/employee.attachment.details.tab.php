<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 December 2015 at 18:04:35 GMT, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';


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


$attachment=$state['_object'];

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Attachment_Subject_Type',
				'edit'=>'option',
				'value'=>$attachment->get('Attachment Subject Type'),
				'formated_value'=>$attachment->get('Subject Type'),
				'options'=>$options_Attachment_Subject_Type,
				'label'=>ucfirst($attachment->get_field_label('Attachment Subject Type')),
				'required'=>true,

				'type'=>'value'
			),

			array(
				'id'=>'Attachment_Caption',
				'edit'=>'string',
				'value'=>$attachment->get('Attachment Caption'),
				'formated_value'=>$attachment->get('Caption'),

				'label'=>$attachment->get_field_label('Attachment Caption'),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true
			),


		)
	),
	array(
		'label'=>_('Restrictions'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Attachment_Public',
				'edit'=>'option',
				'value'=>$attachment->get('Attachment Public'),
				'formated_value'=>$attachment->get('Public'),
				'options'=>$options_yn,
				'label'=>ucfirst($attachment->get_field_label('Attachment Public')),
				'required'=>true,

			)
		)
	),



);

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('object_fields.tpl');

?>
