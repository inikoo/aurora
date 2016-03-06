<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 February 2016 at 09:27:25 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'class.Store.php';

include_once 'utils/invalid_messages.php';


$product=new StoreProduct(0);

$options_Packing_Group=array(
	'None'=>_('None'), 'I'=>'I', 'II'=>'II', 'III'=>'III'
);


$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);



asort($options_yn);


$store=new Store($state['current_store']);
if($store->id){
    $store_value=$store->id;
    $store_formatted_value=$store->get('Name').' ('.$store->get('Code').')';
}

$object_fields=array(

array(
		'label'=>_('Store'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Product_Store_Store_Key',
				'edit'=>'dropdown_select',
				'scope'=>'stores',
				'value'=>$store_value,
				'formatted_value'=>$store_formatted_value,
				'label'=>ucfirst('Store'),
				'required'=>true,
				'type'=>'value'
			),




		)
	),
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Store_Product_Code',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Store Product Code')),
				'formatted_value'=>$product->get('Code'),
				'label'=>ucfirst($product->get_field_label('Store Product Code')),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates','parent'=>'store','parent_key_field'=>'Product_Store_Store_Key')),

				'type'=>'value'
			),




		)
	),

	array(
		'label'=>_('Stock unit'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Product_Unit_Description',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Product Unit Description')),
				'formatted_value'=>$product->get('Unit Description'),
				'label'=>ucfirst($product->get_field_label('Product Unit Description')),
				'required'=>true,
				'type'=>'value'


			),

			array(
				'id'=>'Product_Package_Weight',
				'edit'=>'numeric',
				'value'=>$product->get('Product Package Weight') ,
				'formatted_value'=>$product->get('Package Weight') ,
				'label'=>ucfirst($product->get_field_label('Product Package Weight')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>false,
				'placeholder'=>_('Kg'),

				'type'=>'value'
			),
			array(
				'id'=>'Product_Package_Dimensions',
				'edit'=>'dimensions',
				'value'=>$product->get('Product Package Dimensions') ,
				'formatted_value'=>$product->get('Package Dimensions') ,
				'label'=>ucfirst($product->get_field_label('Product Package Dimensions')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'placeholder'=>_('L x W x H (in cm)'),
				'type'=>'value'
			),


			array(
				'id'=>'Product_Tariff_Code',
				'edit'=>'numeric',
				'value'=>$product->get('Product Tariff Code') ,
				'formatted_value'=>$product->get('Tariff Code') ,
				'label'=>ucfirst($product->get_field_label('Product Tariff Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'type'=>'value'

			),
			array(
				'id'=>'Product_Duty_Rate',
				'edit'=>'numeric',
				'value'=>$product->get('Product Duty Rate') ,
				'formatted_value'=>$product->get('Duty Rate') ,
				'label'=>ucfirst($product->get_field_label('Product Duty Rate')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'type'=>'value'

			)


		)
	),
	array(
		'label'=>_('Health & Safety'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Product_UN_Number',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Product UN Number')),
				'formatted_value'=>$product->get('UN Number'),
				'label'=>ucfirst($product->get_field_label('Product UN Number')),
				'required'=>false
			),
			array(
				'id'=>'Product_UN_Class',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Product UN Class')),
				'formatted_value'=>$product->get('UN Class'),
				'label'=>ucfirst($product->get_field_label('Product UN Class')),
				'required'=>false
			),
			array(
				'id'=>'Product_Packing_Group',
				'edit'=>'option',
				'options'=>$options_Packing_Group,
				'value'=>htmlspecialchars($product->get('Product Packing Group')),
				'formatted_value'=>$product->get('Packing Group'),
				'label'=>ucfirst($product->get_field_label('Product Packing Group')),
				'required'=>false
			),
			array(
				'id'=>'Product_Proper_Shipping_Name',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Product Proper Shipping Name')),
				'formatted_value'=>$product->get('Proper Shipping Name'),
				'label'=>ucfirst($product->get_field_label('Product Proper Shipping Name')),
				'required'=>false
			),
			array(
				'id'=>'Product_Hazard_Indentification_Number',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Product Hazard Indentification Number')),
				'formatted_value'=>$product->get('Hazard Indentification Number'),
				'label'=>ucfirst($product->get_field_label('Product Hazard Indentification Number')),
				'required'=>false
			)
		)






	),

	array(
		'label'=>_('Components'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Product_Materials',
				'edit'=>'textarea',
				'value'=>htmlspecialchars($product->get('Product Materials')),
				'formatted_value'=>$product->get('Materials'),
				'label'=>ucfirst($product->get_field_label('Product Materials')),
				'required'=>false
			),

			array(
				'id'=>'Product_Origin_Country_Code',
				'edit'=>'country',
				'value'=>htmlspecialchars($product->get('Product Origin Country Code')),
				'formatted_value'=>$product->get('Origin Country Code'),
				'label'=>ucfirst($product->get_field_label('Product Origin Country Code')),
				'required'=>false
			),

		)





	)


);
$smarty->assign('state', $state);
$smarty->assign('object', $product);


$smarty->assign('object_name', $product->get_object_name());


$smarty->assign('object_fields', $object_fields);

$html=$smarty->fetch('new_object.tpl');

?>
