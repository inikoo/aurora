<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 13:29:56 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/

$product=new Product('pid',$state['key']);



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'Product_ID',
				'value'=>$product->pid ,
				'label'=>_('ID')
			),
            array(
				'class'=>'locked',
				'id'=>'Product_Key',
				'value'=>$product->id ,
				'label'=>_('Key')
			),
			array(
				'class'=>'string',
				'id'=>'Product_Code',
				'value'=>$product->get('Product Code'),
				'label'=>_('Code')
			),
			array(
				'class'=>'string',
				'id'=>'Product_Name',
				'value'=>$product->get('Product Name'),
				'label'=>_('Name')
			),

		)
	),
	
	array(
		'label'=>_('Unit'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Product_Units_Per_Case',
				'value'=>number($product->get('Product Units Per Case')) ,
				'label'=>_('Units')
			),
            array(
				'id'=>'Product_Unit_Type',
				'value'=>$product->get('Product Unit Type') ,
				'label'=>_('Unit type')
			),
			array(
				'class'=>'string',
				'id'=>'Product_Unit_Container',
				'value'=>$product->get('Product Unit Container'),
				'label'=>_('Unit container')
			)

		)
	),
	array(
		'label'=>_('Export codes'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Product_Tariff_Code',
				'value'=>$product->get('Product Tariff Code') ,
				'label'=>_('Tariff code')
			),
            array(
				'id'=>'Product_Duty_Rate',
				'value'=>$product->get('Product Duty Rate') ,
				'label'=>_('Duty rate')
			)

		)
	),
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
