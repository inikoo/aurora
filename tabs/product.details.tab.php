<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 October 2015 at 13:29:56 CEST,  Fuengirola Spain
 Copyright (c) 2015, Inikoo

 Version 3

*/
include_once 'utils/invalid_messages.php';
$state['_object']->get_store_data();
$product=$state['_object'];

if ($product->get('Store Product Family Category Key')) {
    include_once('class.Category.php');
	$family=new Category($product->get('Store Product Family Category Key'));
	$family_label=$family->get('Code').', '.$family->get('Label');
}else {
	$family_label='';
}


$linked_fields=$product->get_linked_fields_data();

$object_fields=array(
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
				'server_validation'=>'check_for_duplicates'
			),


		)
	),
	array(
		'label'=>_('Parts'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Product_Parts',
				'edit'=>'parts_list',
				'value'=>$product->get('Product Parts') ,
				'formatted_value'=>$product->get('Parts') ,
				'label'=>ucfirst($product->get_field_label('Product Parts')),
				'required'=>false,
			)

		)
	),

	array(
		'label'=>_('Family'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Store_Product_Family_Category_Key',
				'edit'=>'dropdown_select',
				'scope'=>'families',
				'value'=>htmlspecialchars($product->get('Store Product Family Category Key')),
				'formatted_value'=>$family_label,
				'label'=>_('Family'),
				'required'=>true,


			),

			array(
				'id'=>'Store_Product_Label_in_Family',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Store Product Label in Family')),
				'formatted_value'=>$product->get('Label in Family'),
				'label'=>ucfirst($product->get_field_label('Store Product Label in Family')),
				'required'=>true,


			),

		)
	),
	array(
		'label'=>_('Outer'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Store_Product_Outer_Description',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Store Product Outer Description')),
				'formatted_value'=>$product->get('Outer Description'),
				'label'=>ucfirst($product->get_field_label('Store Product Outer Description')),
				'required'=>true,


			),

			array(
				'id'=>'Store_Product_Price',
				'edit'=>'amount',
				'value'=>$product->get('Store Product Price') ,
				'formatted_value'=>$product->get('Price') ,
				'label'=>ucfirst($product->get_field_label('Store Product Price')),
				'invalid_msg'=>get_invalid_message('amount'),
				'required'=>true,
			),
			array(
				'id'=>'Store_Product_Outer_Weight',
				'edit'=>'numeric',
				'value'=>$product->get('Store Product Outer Weight') ,
				'formatted_value'=>$product->get('Outer Weight') ,
				'label'=>ucfirst($product->get_field_label('Store Product Outer Weight')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),
			array(
				'id'=>'Store_Product_Outer_Dimensions',
				'edit'=>'numeric',
				'value'=>$product->get('Store Product Outer Dimensions') ,
				'formatted_value'=>$product->get('Outer Dimensions') ,
				'label'=>ucfirst($product->get_field_label('Store Product Outer Dimensions')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),


			array(
				'id'=>'Store_Product_Outer_Tariff_Code',
				'edit'=>'numeric',
				'value'=>$product->get('Store Product Outer Tariff Code') ,
				'formatted_value'=>$product->get('Outer Tariff Code') ,
				'label'=>ucfirst($product->get_field_label('Store Product Outer Tariff Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,


			),
			array(
				'id'=>'Store_Product_Outer_Duty_Rate',
				'edit'=>'numeric',
				'value'=>$product->get('Store Product Outer Duty Rate') ,
				'formatted_value'=>$product->get('Outer Duty Rate') ,
				'label'=>ucfirst($product->get_field_label('Store Product Outer Duty Rate')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,

			),
			array(
				'id'=>'Store_Product_Units_Per_Outer',
				'edit'=>'small_integer',
				'value'=>$product->get('Store Product Units Per Outer') ,
				'formatted_value'=>$product->get('Units Per Outer') ,
				'label'=>ucfirst($product->get_field_label('Store Product Units Per Outer')),
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
				'edit'=>'options',
				'value'=>$product->get('Store Product Unit Type') ,
				'formatted_value'=>$product->get('Unit Type'),
				'label'=>ucfirst($product->get_field_label('Store Product Unit Type')),
			),

			array(
				'id'=>'Store_Product_Unit_Description',
				'edit'=>'string',
				'value'=>htmlspecialchars($product->get('Store Product Unit Description')),
				'formatted_value'=>$product->get('Unit Description'),
				'label'=>ucfirst($product->get_field_label('Store Product Unit Description')),
				'required'=>true,


			),
			array(
				'id'=>'Store_Product_Unit_Weight',
				'edit'=>'numeric',
				'value'=>$product->get('Store Product Unit Weight') ,
				'formatted_value'=>$product->get('Unit Weight') ,
				'label'=>ucfirst($product->get_field_label('Store Product Unit Weight')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),
			array(
				'id'=>'Store_Product_Unit_Dimensions',
				'edit'=>'numeric',
				'value'=>$product->get('Store Product Unit Dimensions') ,
				'formatted_value'=>$product->get('Unit Dimensions') ,
				'label'=>ucfirst($product->get_field_label('Store Product Unit Dimensions')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),



		)
	),


);

//print_r($linked_fields);
foreach ($object_fields as $key=>$object_field) {
	foreach ( $object_field['fields'] as $key2=>$fields) {
		if (array_key_exists($fields['id'], $linked_fields)) {
			if ($linked_fields[$fields['id']]=='') {
				$object_fields[$key]['fields'][$key2]['label'].=' <i  class="discret fa fa-chain-borken" title="'._('Value indepedient from part').'"></i>';
			}else {
				$object_fields[$key]['fields'][$key2]['label'].=' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>';
				$object_fields[$key]['fields'][$key2]['linked']=true;

			}
		}

	}

}

$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$smarty->assign('parts_list', $product->get_parts_data(true));
$smarty->assign('linked_fields', $linked_fields);




$html=$smarty->fetch('edit_object.tpl');

?>
