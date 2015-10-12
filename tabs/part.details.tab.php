<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 9 October 2015 at 12:43:25 CEST, Malaga Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$part=new Part($state['key']);



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'Part_SKU',
				'value'=>$part->get_formated_id() ,
				'label'=>_('SKU')
			),

			array(
				'class'=>'string',
				'id'=>'Part_Reference',
				'value'=>$part->get('Part Reference'),
				'label'=>_('Reference')
			),
			array(
				'class'=>'string',
				'id'=>'Part_Unit_Description',
				'value'=>$part->get('Part Unit Description'),
				'label'=>_('Name')
			),

		)
	),
	
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
