<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 2 November 2015 at 12:23:33 CET, Lido (Venice), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$psp=$state['_object'];

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			
			
			array(
				'class'=>'string',
				'id'=>'Payment_Service_Provider_Key',
				'value'=>$psp->get('Payment Service Provider Key'),
				'label'=>_('Id')
			),
			array(
				'class'=>'string',
				'id'=>'Payment_Service_Provider_Code',
				'value'=>$psp->get('Payment Service Provider Code'),
				'label'=>_('Code')
			),
			array(
				'class'=>'string',
				'id'=>'Payment_Service_Provider_Name',
				'value'=>$psp->get('Payment Service Provider Name'),
				'label'=>_('Name')
			),

		)
	),

	array(
		'label'=>_('Type'),
		'show_title'=>true,
		'fields'=>array(
			
			
			array(
				'class'=>'option',
				'id'=>'Payment_Service_Provider_Type',
				'value'=>$psp->get_type(),
				'label'=>_('Type')
			)

		)
	),	
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
