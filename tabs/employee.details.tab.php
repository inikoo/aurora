<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 November 2015 at 22:57:18 CET Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$employee=$state['_object'];

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			
			
			array(
				'class'=>'string',
				'id'=>'Staff_Key',
				'value'=>$employee->get('Staff Key'),
				'label'=>_('Id')
			),
			array(
				'class'=>'string',
				'id'=>'Staff_Payroll_Number',
				'value'=>$employee->get('Staff ID'),
				'label'=>_('Payroll Id')
			),
			array(
				'class'=>'string',
				'id'=>'Staff_Alias',
				'value'=>$employee->get('Staff Alias'),
				'label'=>_('Code')
			),
			array(
				'class'=>'string',
				'id'=>'Staff_Name',
				'value'=>$employee->get('Staff Name'),
				'label'=>_('Name')
			),

		)
	),

	
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
