<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 January 2016 at 17:44:49 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/invalid_messages.php';

$manufacture_task=$state['_object'];



$options_Manufacture_Task_Operative_Reward_Terms=array(
	'Above Upper Limit'=>_('Above upper limit'), 'Above Lower Limit'=>_('Above lower limit'), 'Always'=>_('Always'), 'Never'=>_('Never')
);
$options_Manufacture_Task_Operative_Reward_Allowance_Type=array(

	'On top salary'=>_('On top salary'), 'Offset Salary'=>_('Offset Salary')
);

asort($options_Manufacture_Task_Operative_Reward_Terms);
asort($options_Manufacture_Task_Operative_Reward_Allowance_Type);


$object_fields=array(
	array(
		'label'=>_('Description'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(


			array(
				'render'=>true,
				'id'=>'Manufacture_Task_Code',
				'edit'=>'string',
				'value'=>$manufacture_task->get('Manufacture Task Code'),
				'formatted_value'=>$manufacture_task->get('Code'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Code')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'type'=>'value'

			),
			array(
				'render'=>true,
				'id'=>'Manufacture_Task_Name',
				'edit'=>'string',
				'value'=>$manufacture_task->get('Manufacture Task Name'),
				'formatted_value'=>$manufacture_task->get('Name'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Name')),
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates')),
				'type'=>'value'

			),




		)
	),
	array(
		'label'=>_('Costs'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(



			array(
				'render'=>true,
				'required'=>false,
				'id'=>'Manufacture_Task_Work_Cost',
				'edit'=>'numeric',
				'value'=>$manufacture_task->get('Manufacture Task Work Cost'),
				'formatted_value'=>$manufacture_task->get('Work Cost'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Work Cost')),
				'type'=>'value'

			),
			array(
				'render'=>true,
				'required'=>false,
				'id'=>'Manufacture_Task_Materials_Cost',
				'edit'=>'numeric',
				'value'=>$manufacture_task->get('Manufacture Task Materials Cost'),
				'formatted_value'=>$manufacture_task->get('Materials Cost'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Materials Cost')),
				'type'=>'value'

			),
			array(
				'render'=>true,
				'required'=>false,
				'id'=>'Manufacture_Task_Energy_Cost',
				'edit'=>'numeric',
				'value'=>$manufacture_task->get('Manufacture Task Energy Cost'),
				'formatted_value'=>$manufacture_task->get('Energy Cost'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Energy Cost')),
				'type'=>'value'

			),
			array(
				'render'=>true,
				'required'=>false,
				'id'=>'Manufacture_Task_Other_Cost',
				'edit'=>'numeric',
				'value'=>$manufacture_task->get('Manufacture Task Other Cost'),
				'formatted_value'=>$manufacture_task->get('Other Cost'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Other Cost')),
				'type'=>'value'

			),




		)
	),
	array(
		'label'=>_('Targets & employee rewards'),
		'show_title'=>true,
		'class'=>'edit_fields',
		'fields'=>array(

/*

			array(
				'render'=>true,
				'required'=>true,
				'id'=>'Manufacture_Task_Lower_Target',
				'edit'=>'mediumint_unsigned',
				'value'=>$manufacture_task->get('Manufacture Task Lower Target'),
				'formatted_value'=>$manufacture_task->get('Lower Target'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Lower Target')),
				'type'=>'value'

			),
			array(
				'render'=>true,
				'required'=>true,
				'id'=>'Manufacture_Task_Upper_Target',
				'edit'=>'mediumint_unsigned',
				'value'=>$manufacture_task->get('Manufacture Task Upper Target'),
				'formatted_value'=>$manufacture_task->get('Upper Target'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Upper Target')),
				'type'=>'value'

			),
			
			*/
				array(
				'render'=>true,
				'required'=>true,
				'id'=>'Manufacture_Task_Lower_Target_Per_Hour',
				'edit'=>'numeric',
				'value'=>$manufacture_task->get('Manufacture Task Lower Target Per Hour'),
				'formatted_value'=>$manufacture_task->get('Lower Target Per Hour'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Lower Target Per Hour')),
				'type'=>'value'

			),
			array(
				'render'=>true,
				'required'=>true,
				'id'=>'Manufacture_Task_Upper_Target_Per_Hour',
				'edit'=>'numeric',
				'value'=>$manufacture_task->get('Manufacture Task Upper Target Per Hour'),
				'formatted_value'=>$manufacture_task->get('Upper Target Per Hour'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Upper Target Per Hour')),
				'type'=>'value'

			),
			
			array(

				'id'=>'Manufacture_Task_Operative_Reward_Terms',
				'required'=>true,
				'edit'=>'option',
				'value'=>$manufacture_task->get('Manufacture Task Operative Reward Terms'),
				'formatted_value'=>$manufacture_task->get('Operative Reward Terms'),
				'options'=>$options_Manufacture_Task_Operative_Reward_Terms,
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Operative Reward Terms')),
				'type'=>'value'
			),

			array(

				'id'=>'Manufacture_Task_Operative_Reward_Allowance_Type',
				'required'=>true,
				'edit'=>'option',
				'value'=>$manufacture_task->get('Manufacture Task Operative Reward Allowance Type'),
				'formatted_value'=>$manufacture_task->get('Operative Reward Allowance Type'),
				'options'=>$options_Manufacture_Task_Operative_Reward_Allowance_Type,
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Operative Reward Allowance Type')),
				'type'=>'value'
			),
			array(
				'render'=>true,
				'required'=>true,
				'id'=>'Manufacture_Task_Operative_Reward_Amount',
				'edit'=>'numeric',
				'value'=>$manufacture_task->get('Manufacture Task Operative Reward Amount'),
				'formatted_value'=>$manufacture_task->get('Operative Reward Amount'),
				'label'=>ucfirst($manufacture_task->get_field_label('Manufacture Task Operative Reward Amount')),
				'type'=>'value'

			),

		)
	)
);




$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);

$html=$smarty->fetch('edit_object.tpl');

?>
