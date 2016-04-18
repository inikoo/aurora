<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 April 2016 at 15:01:58 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

if (isset($options['new']) and  $options['new'] ) {
	$new=true;
}else {
	$new=false;
}



if ($object->get('Store Product Family Category Key')) {
	include_once 'class.Category.php';
	$family=new Category($object->get('Store Product Family Category Key'));
	$family_label=$family->get('Code').', '.$family->get('Label');
}else {
	$family_label='';
}


$linked_fields=$object->get_linked_fields_data();

$product_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Store_Product_Code',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Store Product Code')),
				'formatted_value'=>$object->get('Code'),
				'label'=>ucfirst($object->get_field_label('Store Product Code')),
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
				'value'=>$object->get('Product Parts') ,
				'formatted_value'=>$object->get('Parts') ,
				'label'=>ucfirst($object->get_field_label('Product Parts')),
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
				'value'=>htmlspecialchars($object->get('Store Product Family Category Key')),
				'formatted_value'=>$family_label,
				'stripped_formatted_value'=>$family_label,
				'label'=>_('Family'),
				'required'=>true,


			),

			array(
				'id'=>'Store_Product_Label_in_Family',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Store Product Label in Family')),
				'formatted_value'=>$object->get('Label in Family'),
				'label'=>ucfirst($object->get_field_label('Store Product Label in Family')),
				'required'=>true,


			),

		)
	),
	
	array(
		'label'=>_('Outer'),
		'show_title'=>true,
		'fields'=>array(
		
		/*
		
			array(
				'id'=>'Store_Product_Outer_Description',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Store Product Outer Description')),
				'formatted_value'=>$object->get('Outer Description'),
				'label'=>ucfirst($object->get_field_label('Store Product Outer Description')),
				'required'=>true,


			),
*/



			array(
				'id'=>'Store_Product_Price',
				'edit'=>($edit?'amount':''),

				'value'=>$object->get('Store Product Price') ,
				'formatted_value'=>$object->get('Price') ,
				'label'=>ucfirst($object->get_field_label('Store Product Price')),
				'invalid_msg'=>get_invalid_message('amount'),
				'required'=>true,
			),

/*
			array(
				'id'=>'Store_Product_Outer_Weight',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Store Product Outer Weight') ,
				'formatted_value'=>$object->get('Outer Weight') ,
				'label'=>ucfirst($object->get_field_label('Store Product Outer Weight')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),
			array(
				'id'=>'Store_Product_Outer_Dimensions',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Store Product Outer Dimensions') ,
				'formatted_value'=>$object->get('Outer Dimensions') ,
				'label'=>ucfirst($object->get_field_label('Store Product Outer Dimensions')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),


			array(
				'id'=>'Store_Product_Outer_Tariff_Code',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Store Product Outer Tariff Code') ,
				'formatted_value'=>$object->get('Outer Tariff Code') ,
				'label'=>ucfirst($object->get_field_label('Store Product Outer Tariff Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,


			),
			array(
				'id'=>'Store_Product_Outer_Duty_Rate',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Store Product Outer Duty Rate') ,
				'formatted_value'=>$object->get('Outer Duty Rate') ,
				'label'=>ucfirst($object->get_field_label('Store Product Outer Duty Rate')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,

			),
			array(
				'id'=>'Store_Product_Units_Per_Outer',
				'edit'=>'small_integer',
				'value'=>$object->get('Store Product Units Per Outer') ,
				'formatted_value'=>$object->get('Units Per Outer') ,
				'label'=>ucfirst($object->get_field_label('Store Product Units Per Outer')),
				'invalid_msg'=>get_invalid_message('small_integer'),
				'required'=>true,
			),
*/
		)
	),
	
	array(
		'label'=>_('Retail unit'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Product_Unit_Type',
				'edit'=>'options',
				'value'=>$object->get('Store Product Unit Type') ,
				'formatted_value'=>$object->get('Unit Type'),
				'label'=>ucfirst($object->get_field_label('Store Product Unit Type')),
			),

			array(
				'id'=>'Store_Product_Unit_Description',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Store Product Unit Description')),
				'formatted_value'=>$object->get('Unit Description'),
				'label'=>ucfirst($object->get_field_label('Store Product Unit Description')),
				'required'=>true,


			),
			array(
				'id'=>'Store_Product_Unit_Weight',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Store Product Unit Weight') ,
				'formatted_value'=>$object->get('Unit Weight') ,
				'label'=>ucfirst($object->get_field_label('Store Product Unit Weight')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),
			array(
				'id'=>'Store_Product_Unit_Dimensions',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Store Product Unit Dimensions') ,
				'formatted_value'=>$object->get('Unit Dimensions') ,
				'label'=>ucfirst($object->get_field_label('Store Product Unit Dimensions')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),



		)
	),


);

//print_r($linked_fields);
foreach ($product_fields as $key=>$object_field) {
	foreach ( $object_field['fields'] as $key2=>$fields) {
		if (array_key_exists($fields['id'], $linked_fields)) {
			if ($linked_fields[$fields['id']]=='') {
				$product_fields[$key]['fields'][$key2]['label'].=' <i  class="discret fa fa-chain-borken" title="'._('Value indepedient from part').'"></i>';
			}else {
				$product_fields[$key]['fields'][$key2]['label'].=' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>';
				$product_fields[$key]['fields'][$key2]['linked']=true;

			}
		}

	}

}

?>
