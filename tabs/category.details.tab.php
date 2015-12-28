<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 December 2015 at 12:47:50 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';

$category=$state['_object'];


$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			
			
			array(
				'edit'=>'string',
				'id'=>'Category_Code',
				'value'=>$category->get('Category Code'),
				'formated_value'=>$category->get('Code'),
				'label'=>ucfirst($category->get_field_label('Category Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'server_validation'=>'check_for_duplicates',
			),
				array(
				'edit'=>'string',
				'id'=>'Category_Label',
				'value'=>$category->get('Category Label'),
				'formated_value'=>$category->get('Label'),
				'label'=>ucfirst($category->get_field_label('Category Label')),
				'invalid_msg'=>get_invalid_message('string'),
			),

		)
	),
	
	
	
);

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('object_fields.tpl');

?>
