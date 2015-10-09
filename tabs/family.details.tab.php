<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 October 2015 at 21:31:00 BST, Birmingham->Malaga (Plane)
 Copyright (c) 2015, Inikoo

 Version 3

*/


$store=new Store($state['key']);

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'Product_Family_Key',
				'value'=>$store->id  ,
				'label'=>_('Id')
			),
            array(
				'class'=>'string',
				'id'=>'Product_Family_Code',
				'value'=>$store->get('Store Code')  ,
				'label'=>_('Code')
			),
			array(
				'class'=>'string',
				'id'=>'Store_Name',
				'value'=>$store->get('Store Name'),
				'label'=>_('Name')
			),
			

		)
	)
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>