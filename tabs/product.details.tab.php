<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 13:29:56 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';

$product=$state['_object'];



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'render'=>false,
				'class'=>'locked',
				'id'=>'Product_ID',
				'value'=>$product->pid ,
				'label'=>_('ID')
			),
			array(
				'render'=>false,
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
		'label'=>_('Outer'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Product_Price',
				'edit'=>'amount',
				'value'=>$product->get('Product Price') ,
				'formatted_value'=>$product->get('Price') ,
				'label'=>ucfirst($product->get_field_label('Product Price')),
				'invalid_msg'=>get_invalid_message('amount'),
				'required'=>true,
			),

			array(
				'id'=>'Product_Units_Per_Case',
				'edit'=>'small_integer',
				'value'=>$product->get('Product Units Per Case') ,
				'formatted_value'=>$product->get('Units Per Case') ,
				'label'=>ucfirst($product->get_field_label('Product Units Per Case')),
				'invalid_msg'=>get_invalid_message('small_integer'),
				'required'=>true,
			),



		)
	), array(
		'label'=>_('Unit'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Product_Unit_Type',
				'value'=>$product->get('Product Unit Type') ,
				'label'=>_('Unit type')
			),
			array(
				'id'=>'Product_Retail_Units_Per_Unit',
				'edit'=>'small_integer',
				'value'=>$product->get('Product Retail Units Per Unit') ,
				'formatted_value'=>$product->get('Retail Units Per Unit') ,
				'label'=>ucfirst($product->get_field_label('Product Retail Units Per Unit')),
				'invalid_msg'=>get_invalid_message('small_integer'),
				'required'=>true,
			),

		)
	),
	array(
		'label'=>_('Retail unit'),
		'show_title'=>true,
		'fields'=>array(
		
			array(
				'id'=>'Product_Unit_Type',
				'value'=>$product->get('Product Unit Type') ,
				'label'=>_('Unit type')
			),


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
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html=$smarty->fetch('edit_object.tpl');

?>
