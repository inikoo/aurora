<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 June 2016 at 11:49:19 CEST, Mijas Costa, Spain

 Copyright (c) 2016, Inikoo

 Version 3.0
*/

$public_options=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);
asort($public_options);


$category_product_fields=array(
	array(
		'label'=>_('Visibility'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'edit'=>'option',
				'id'=>'Product_Category_Public',
				'options'=>$public_options,
				'value'=> $object->get('Product Category Public'),
				'formatted_value'=> $object->get('Public'),
				'label'=>_('Public'),
				'type'=>'value'
			),

			array(

				'id'=>'Product_Category_Description',
				'edit'=>($edit?'html_editor':''),

				'value'=>htmlentities($object->get('Product Category Description')),
				'formatted_value'=>$object->get('Description'),
				'label'=>ucfirst($object->get_field_label('Product Category Description')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
				'type'=>'value'
			),

		)
	),



);


?>
