<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 April 2016 at 15:01:58 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


include_once 'utils/static_data.php';

if ($user->can_edit('parts')) {
	$part_edit=true;
}else {
	$part_edit=false;

}

$options_Packing_Group=array(
	'None'=>_('None'), 'I'=>'I', 'II'=>'II', 'III'=>'III'
);

$options_status=array('Active'=>_('Active'), 'Suspended'=>_('Suspended'), 'Discontinued'=>_('Discontinued'));
$options_web_configuration=array( 'Online Auto'=>_('Automatic'), 'Online Force For Sale'=>_('For sale').' <i class="fa fa-thumb-tack" aria-hidden="true"></i>' , 'Online Force Out of Stock'=>_('Out of stock'), 'Offline'=>_('Offline'));


$parts_data=$object->get_parts_data();
$number_parts=count($parts_data);
$linked_fields=array();
if ($number_parts==1 and isset($parts_data[0]['Linked Fields']) and is_array($parts_data[0]['Linked Fields']) ) {
	$linked_fields=array_flip($parts_data[0]['Linked Fields']);
}

if (count($object->get_parts())==1 ) {

	$fields_linked=true;
}else {
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
		'label'=>_('Status'),
		'show_title'=>true,
		'class'=>'hide',
		'fields'=>array(
			array(
				'render'=>($new?false:true),
				'id'=>'Product_Status',
				'edit'=>($edit?'option':''),

				'options'=>$options_status,
				'value'=>htmlspecialchars($object->get('Product Status')),
				'formatted_value'=>$object->get('Status'),
				'label'=>ucfirst($object->get_field_label('Product Status')),
				'required'=>($new?false:true),
				'type'=>'skip'
			),
			array(
				'render'=>($new?false: ($object->get('Product Status')=='Active'?true:false)),
				'id'=>'Product_Web_Configuration',
				'edit'=>($edit?'option':''),

				'options'=>$options_web_configuration,
				'value'=>htmlspecialchars($object->get('Product Web Configuration')),
				'formatted_value'=>$object->get('Web Configuration'),
				'label'=>ucfirst($object->get_field_label('Web Configuration')),
				'required'=>($new?false:true),
				'type'=>'skip'
			),
		)
	),


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
				'type'=>'value'
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
				'type'=>'value'
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
				'parent_key'=>($new?$options['store_key']:$object->get('Product Store Key')),
				'value'=>htmlspecialchars($object->get('Product Family Category Key')),
				'formatted_value'=>$object->get('Family Category Key'),
				'stripped_formatted_value'=>'',
				'label'=>_('Family'),
				'required'=>true,
				'type'=>'value'


			),

			array(
				'id'=>'Product_Label_in_Family',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Product Label in Family')),
				'formatted_value'=>$object->get('Label in Family'),
				'label'=>ucfirst($object->get_field_label('Product Label in Family')),
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
				'type'=>'value'
			),
			
			array(
				'id'=>'Product_Cost',
				'edit'=>($edit?'amount':''),

				'value'=>($new?0:$object->get('Product Cost')) ,
				'formatted_value'=>($new?0:$object->get('Cost')) ,
				'label'=>ucfirst($object->get_field_label('Product Cost')),
				'invalid_msg'=>get_invalid_message('amount'),
				'required'=>true,
				'type'=>'value'
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

				'value'=>($new?_('piece'):$object->get('Product Unit Label')) ,
				'formatted_value'=>($new?_('piece'):$object->get('Unit Label')),
				'label'=>ucfirst($object->get_field_label('Product Unit Label')),
				'required'=>true,
				'type'=>'value'

			),

			array(
				'id'=>'Product_Name',
				'edit'=>($edit?'string':''),

				'value'=>htmlspecialchars($object->get('Product Name')),
				'formatted_value'=>$object->get('Name'),
				'label'=>ucfirst($object->get_field_label('Product Name')),
				'required'=>true,
				'type'=>'value'


			),

			array(
				'id'=>'Product_Unit_RRP',
				'edit'=>($edit?'amount':''),

				'value'=>$object->get('Product Unit RRP') ,
				'formatted_value'=>$object->get('Unit RRP') ,
				'label'=>ucfirst($object->get_field_label('Product Unit RRP')),
				'invalid_msg'=>get_invalid_message('amount'),
				'required'=>false,
				'type'=>'value'
			),

			array(
				'id'=>'Product_Unit_Weight',
				'edit'=>($part_edit?'numeric':''),
				'render'=>($new?false:true),
				'value'=>$object->get('Product Unit Weight') ,
				'formatted_value'=>$object->get('Unit Weight') ,
				'label'=>ucfirst($object->get_field_label('Product Unit Weight')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':'')   ,
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Product_Unit_Dimensions',
				'edit'=>($part_edit?'string':''),
				'render'=>($new?false:true),
				'value'=>$object->get('Product Unit Dimensions') ,
				'formatted_value'=>$object->get('Unit Dimensions') ,
				'label'=>ucfirst($object->get_field_label('Product Unit Dimensions')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'invalid_msg'=>get_invalid_message('numeric'),
				'required'=>false,
				'type'=>'value'
			),



		)
	),


	array(
		'label'=>_('Properties'),
		'show_title'=>true,
		'class'=>($new?'hide':''),
		'fields'=>array(

			array(
				'id'=>'Product_Materials',
				//'linked'=>$fields_linked,
				'edit'=>($part_edit?'textarea':''),

 'render'=>($new?false:true),

				'value'=>htmlspecialchars($object->get('Product Materials')),
				'formatted_value'=>$object->get('Materials'),
				'label'=>ucfirst($object->get_field_label('Product Materials')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':'')   ,
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Product_Origin_Country_Code',
				'edit'=>($part_edit?'country_select':''),
 'render'=>($new?false:true),
				'options'=>get_countries($db),
				'scope'=>'countries',
				'value'=>$object->get('Product Origin Country Code'),
				'formatted_value'=>$object->get('Origin Country Code'),
				'stripped_formatted_value'=>($object->get('Product Origin Country Code')!=''?  $object->get('Origin Country').' ('.$object->get('Product Origin Country Code').')':''),
				'label'=>ucfirst($object->get_field_label('Product Origin Country Code')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'required'=>false,
				//'type'=>'value'
			),
			array(
				'id'=>'Product_Tariff_Code',
				'edit'=>($part_edit?'numeric':''),
 'render'=>($new?false:true),
				'value'=>$object->get('Product Tariff Code') ,
				'formatted_value'=>$object->get('Tariff Code') ,
				'label'=>ucfirst($object->get_field_label('Product Tariff Code')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'type'=>'value'

			),
			array(
				'id'=>'Product_Duty_Rate',
				'edit'=>($part_edit?'string':''),
 'render'=>($new?false:true),
				'value'=>$object->get('Product Duty Rate') ,
				'formatted_value'=>$object->get('Duty Rate') ,
				'label'=>ucfirst($object->get_field_label('Product Duty Rate')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'type'=>'value'
			),

		)
	),

	array(
		'label'=>_('Health & Safety'),
		'class'=>($new?'hide':''),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Product_UN_Number',
				'edit'=>($part_edit?'string':''),
				'render'=>($new?false:true),
				'value'=>htmlspecialchars($object->get('Product UN Number')),
				'formatted_value'=>$object->get('UN Number'),
				'label'=>ucfirst($object->get_field_label('Product UN Number')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Product_UN_Class',
				'edit'=>($part_edit?'string':''),
				'render'=>($new?false:true),
				'value'=>htmlspecialchars($object->get('Product UN Class')),
				'formatted_value'=>$object->get('UN Class'),
				'label'=>ucfirst($object->get_field_label('Product UN Class')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Product_Packing_Group',
				'edit'=>($part_edit?'option':''),
				'render'=>($new?false:true),
				'options'=>$options_Packing_Group,
				'value'=>htmlspecialchars($object->get('Product Packing Group')),
				'formatted_value'=>$object->get('Packing Group'),
				'label'=>ucfirst($object->get_field_label('Product Packing Group')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Product_Proper_Shipping_Name',
				'edit'=>($part_edit?'string':''),
				'render'=>($new?false:true),
				'value'=>htmlspecialchars($object->get('Product Proper Shipping Name')),
				'formatted_value'=>$object->get('Proper Shipping Name'),
				'label'=>ucfirst($object->get_field_label('Product Proper Shipping Name')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Product_Hazard_Indentification_Number',
				'edit'=>($part_edit?'string':''),
				'render'=>($new?false:true),
				'value'=>htmlspecialchars($object->get('Product Hazard Indentification Number')),
				'formatted_value'=>$object->get('Hazard Indentification Number'),
				'label'=>ucfirst($object->get_field_label('Product Hazard Indentification Number')).($fields_linked?' <i  class="discret fa fa-chain"  title="'._('Linked to part value').'"></i>':''),
				'required'=>false,
				'type'=>'value'
			)
		)






	)


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
