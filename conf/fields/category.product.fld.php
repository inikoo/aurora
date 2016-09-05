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
				'edit'=>($edit?'editor':''),
				'class'=>'editor',
				'editor_data'=>array(
					'id'=>'Product_Category_Description',
					'content'=>$object->get('Product Category Description'),

					'data'=>base64_encode(json_encode(array(
								'mode'=>'edit_object',
								'field'=>'Product_Category_Description',
								'plugins'=>array('align', 'draggable', 'image', 'link', 'save', 'entities', 'emoticons', 'fullscreen', 'lineBreaker', 'table', 'codeView', 'codeBeautifier'),
								'metadata'=>array(
									'tipo'=>'edit_field',
									'object'=>'Category',
									'key'=>$object->id,
									'field'=>'Product Category Description',



								)
							)
						))

				),
				'value'=>$object->get('Product Category Description'),
				'formatted_value'=>$object->get('Product Category Description'),
				'label'=>ucfirst($object->get_field_label('Product Category Description')),
				'required'=>false,
				'type'=>'value'
			),

		

		)
	),



);


?>
