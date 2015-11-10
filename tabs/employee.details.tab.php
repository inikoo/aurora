<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 November 2015 at 22:57:18 CET Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';

$employee=$state['_object'];

$options_Staff_Type=array(
	'Employee'=>_('Employee'), 'Volunteer'=>_('Volunteer'), 'Contractor'=>_('Contractor'), 'TemporalWorker'=>_('Temporal Worker'), 'WorkExperience'=>_('Work Experience')
);

$options_Staff_Currently_Working=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);

asort($options_Staff_Type);
asort($options_Staff_Currently_Working);

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(


			array(

				'id'=>'Staff_Key',
				'value'=>$employee->get('Staff Key'),
				'label'=>_('Id')
			),
			array(

				'id'=>'Staff_ID',
				'edit'=>'smallint_unsigned',
				'value'=>$employee->get('Staff ID'),
				'label'=>_('Payroll Id'),
				'invalid_msg'=>get_invalid_message('smallint_unsigned'),
				'server_validation'=>'check_for_duplicates'
			),
			array(

				'id'=>'Staff_Alias',
				'edit'=>'string',
				'value'=>$employee->get('Staff Alias'),
				'label'=>_('Code'),
				'server_validation'=>'check_for_duplicates'
			),
			array(

				'id'=>'Staff_Name',
				'edit'=>'string',
				'value'=>$employee->get('Staff Name'),
				'label'=>_('Name')
			),

		)
	),
	array(
		'label'=>_('Employment'),
		'show_title'=>true,
		'fields'=>array(
			array(

				'id'=>'Staff_Type',
				'edit'=>'option',
				'value'=>$employee->get('Staff Type'),
				'formated_value'=>$employee->get('Type'),
				'options'=>$options_Staff_Type,
				'label'=>_('Type')
			),

			array(

				'id'=>'Staff_Currently_Working',
				'edit'=>'option',
				'value'=>$employee->get('Staff Currently Working'),
				'formated_value'=>$employee->get('Currently Working'),
				'options'=>$options_Staff_Currently_Working,
				'label'=>_('Currently working')
			),
			array(

				'id'=>'Staff_Valid_From',
				'edit'=>'date',
				'time'=>'09:00:00',
				'value'=>$employee->get('Staff Valid From'),
				'formated_value'=>$employee->get('Valid From'),
				'label'=>_('Working from'),
				'invalid_msg'=>get_invalid_message('date'),
			),
			array(
                'render'=>($employee->get('Staff Currently Working')=='Yes'?false:true),
				'id'=>'Staff_Valid_To',
				'edit'=>'date',
				'time'=>'17:00:00',
				'value'=>$employee->get('Staff Valid To'),
				'formated_value'=>$employee->get('Valid To'),
				'label'=>_('End of employement'),
				'invalid_msg'=>get_invalid_message('date'),
			),



		)
	),


);


$smarty->assign('state', $state);

$smarty->assign('object_fields', $object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
