<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 April 2016 at 15:01:58 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



$parts_data=$object->get_parts_data();
$number_parts=count($parts_data);
$linked_fields=array();
if ($number_parts==1 and isset($parts_data[0]['Linked Fields']) and is_array($parts_data[0]['Linked Fields']) ) {
	$linked_fields=array_flip($parts_data[0]['Linked Fields']);
}

if(count($object->get_parts())==1 ){

$fields_linked=true;
}else{
$fields_linked=false;

}

if (isset($options['new']) and  $options['new'] ) {
	$new=true;
}else {
	$new=false;
}


$options_Unit_Type=array(
	'Piece'=>_('Piece'), 'Gram'=>_('Gram'), 'Liter'=>_('Liter')
);
asort($options_Unit_Type);


if ($object->get('Product Family Category Key')) {
	include_once 'class.Category.php';
	$family=new Category($object->get('Product Family Category Key'));
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
				'id'=>'Product_Code',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Product Code')),
				'formatted_value'=>$object->get('Code'),
				'label'=>ucfirst($object->get_field_label('Product Code')),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates', 'parent'=>'store', 'parent_key'=>$object->get('Product Store Key'), 'object'=>'Product', 'key'=>$object->id)),

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
				'id'=>'Product_Family_Category_Key',
				'edit'=>'dropdown_select',
				'scope'=>'families',
				'parent'=>'store',
				'parent_key'=>$object->get('Product Store Key'),
				'value'=>htmlspecialchars($object->get('Product Family Category Key')),
				'formatted_value'=>$object->get('Family Category Key'),
				'stripped_formatted_value'=>'',
				'label'=>_('Family'),
				'required'=>true,


			),

			array(
				'id'=>'Product_Label_in_Family',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Product Label in Family')),
				'formatted_value'=>$object->get('Label in Family'),
				'label'=>ucfirst($object->get_field_label('Product Label in Family')),
				'required'=>false,


			),

		)
	),
	array(
		'label'=>_('Retail unit'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Product_Unit_Label',
				'edit'=>($edit?'string':''),

				'value'=>$object->get('Product Unit Label') ,
				'formatted_value'=>$object->get('Unit Label'),
				'label'=>ucfirst($object->get_field_label('Product Unit Label')),
				'required'=>true,

			),

			array(
				'id'=>'Product_Name',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Product Name')),
				'formatted_value'=>$object->get('Name'),
				'label'=>ucfirst($object->get_field_label('Product Name')),
				'required'=>true,


			),

			array(
				'id'=>'Product_Unit_RRP',
				'edit'=>($edit?'amount':''),

				'value'=>$object->get('Product Unit RRP') ,
				'formatted_value'=>$object->get('Unit RRP') ,
				'label'=>ucfirst($object->get_field_label('Product Unit RRP')),
				'invalid_msg'=>get_invalid_message('amount'),
				'required'=>false,
			),

			array(
				'id'=>'Product_Unit_Weight',
				'edit'=>  ($edit?
					($fields_linked?'linked':'numeric'):''),
 'linked'=>$fields_linked,
				'value'=>$object->get('Product Unit Weight') ,
				'formatted_value'=>$object->get('Unit Weight') ,
				'label'=>ucfirst($object->get_field_label('Product Unit Weight')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':'')   ,
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),
			array(
				'id'=>'Product_Unit_Dimensions',
				'edit'=>  ($edit?
					($fields_linked?'linked':'string'):''),
 'linked'=>$fields_linked,
				'value'=>$object->get('Product Unit Dimensions') ,
				'formatted_value'=>$object->get('Unit Dimensions') ,
				'label'=>ucfirst($object->get_field_label('Product Unit Dimensions')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':'')   ,
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),
			array(
				'id'=>'Product_Materials',
                'linked'=>$fields_linked,
				'edit'=>  ($edit?
					($fields_linked?'linked':'textarea'):''),


				'value'=>htmlspecialchars($object->get('Product Materials')),
				'formatted_value'=>$object->get('Materials'),
				'label'=>ucfirst($object->get_field_label('Product Materials')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':'')   ,
				'required'=>false,
				'type'=>'value'
			),


		)
	),
	array(
		'label'=>_('Outer'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'render'=>true,

				'id'=>'Product_Units_Per_Case',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Product Units Per Case') ,
				'formatted_value'=>$object->get('Units Per Case') ,
				'label'=>ucfirst($object->get_field_label('Product Units Per Case')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'type'=>'value'
			),


			array(
				'id'=>'Product_Price',
				'edit'=>($edit?'amount':''),

				'value'=>$object->get('Product Price') ,
				'formatted_value'=>$object->get('Price') ,
				'label'=>ucfirst($object->get_field_label('Product Price')),
				'invalid_msg'=>get_invalid_message('amount'),
				'required'=>true,
			),

			/*
			array(
				'id'=>'Product_Outer_Weight',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Product Outer Weight') ,
				'formatted_value'=>$object->get('Outer Weight') ,
				'label'=>ucfirst($object->get_field_label('Product Outer Weight')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),
			array(
				'id'=>'Product_Outer_Dimensions',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Product Outer Dimensions') ,
				'formatted_value'=>$object->get('Outer Dimensions') ,
				'label'=>ucfirst($object->get_field_label('Product Outer Dimensions')),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>true,
			),


			array(
				'id'=>'Product_Outer_Tariff_Code',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Product Outer Tariff Code') ,
				'formatted_value'=>$object->get('Outer Tariff Code') ,
				'label'=>ucfirst($object->get_field_label('Product Outer Tariff Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,


			),
			array(
				'id'=>'Product_Outer_Duty_Rate',
				'edit'=>($edit?'numeric':''),

				'value'=>$object->get('Product Outer Duty Rate') ,
				'formatted_value'=>$object->get('Outer Duty Rate') ,
				'label'=>ucfirst($object->get_field_label('Product Outer Duty Rate')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,

			),
			array(
				'id'=>'Product_Units_Per_Outer',
				'edit'=>'small_integer',
				'value'=>$object->get('Product Units Per Outer') ,
				'formatted_value'=>$object->get('Units Per Outer') ,
				'label'=>ucfirst($object->get_field_label('Product Units Per Outer')),
				'invalid_msg'=>get_invalid_message('small_integer'),
				'required'=>true,
			),
*/
		)
	),




);

/*
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
*/

?>
