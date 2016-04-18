<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 20 September 2015 13:22:27 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/


if ( !$user->can_view('locations') or   !in_array($state['key'], $user->warehouses)   ) {
	$html='';
}else {

	include_once 'utils/invalid_messages.php';

	$warehouse=$state['_object'];



	$object_fields=array(
		array(
			'label'=>_('Id'),
			'show_title'=>true,
			'fields'=>array(

				array(
					'edit'=>'string',
					'id'=>'Warehouse_Code',
					'value'=>$warehouse->get('Warehouse Code')  ,
					'label'=>ucfirst($warehouse->get_field_label('Warehouse Code')),
					'invalid_msg'=>get_invalid_message('string'),
					'required'=>true,
					'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				),
				array(
					'edit'=>'string',
					'id'=>'Warehouse_Name',
					'value'=>$warehouse->get('Warehouse Name')  ,
					'label'=>ucfirst($warehouse->get_field_label('Warehouse Name')),
					'invalid_msg'=>get_invalid_message('string'),
					'required'=>true,
				),

			)
		),


	);
	$smarty->assign('object', $state['_object']);
	$smarty->assign('key', $state['key']);

	$smarty->assign('object_fields', $object_fields);
	$smarty->assign('state', $state);


	$html=$smarty->fetch('edit_object.tpl');
}

?>
