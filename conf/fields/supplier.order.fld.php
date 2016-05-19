<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 12 May 2016 at 10:43:19 GMT+8, Kuala Lumpur, Malysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/


$options_Incoterm=array();
$sql=sprintf('select `Incoterm Code`,`Incoterm Name`,`Incoterm Transport Type` from kbase.`Incoterm Dimension`  ');
foreach ($db->query($sql) as $row) {
	$options_Incoterm[$row['Incoterm Code']]='<b>'.$row['Incoterm Code'].'</b> '.$row['Incoterm Name'];
}
asort($options_Incoterm);



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'id'=>'Purchase_Order_Public_ID',
				'edit'=>($edit?'string':''),
				'value'=>htmlspecialchars($object->get('Purchase Order Public ID')),
				'formatted_value'=>$object->get('Public ID'),
				'label'=>ucfirst($object->get_field_label('Purchase Order Public ID')),
				'required'=>true,
				'server_validation'=>json_encode(array('tipo'=>'check_for_duplicates', 'parent'=>$object->get('Purchase Order Parent'), 'parent_key'=>$object->get('Purchase Order Parent Key'), 'object'=>'PurchaseOrder', 'key'=>$object->id)),
				'type'=>'value'
			),




		)
	),
	array(
		'label'=>_('Estimated delivery'),
		'show_title'=>true,
		'fields'=>array(

			array(


				'id'=>'Purchase_Order_Estimated_Receiving_Date',
				'edit'=>($edit?'date':''),

				'time'=>'00:00:00',
				'value'=>$object->get('Purchase Order Estimated Receiving Date'),
				'formatted_value'=>$object->get('Estimated Receiving Date'),
				'label'=>ucfirst($object->get_field_label('Purchase Order Estimated Receiving Date')),
				'invalid_msg'=>get_invalid_message('date'),
				'required'=>false,
				'type'=>'value'


			),
			




		)
	),
	array(
		'label'=>_('Delivery rules'),
		'show_title'=>true,
		'fields'=>array(

			array(


				'id'=>'Purchase_Order_Incoterm',
				'edit'=>($edit?'option':''),
				'options'=>$options_Incoterm,
				'value'=>htmlspecialchars($object->get('Purchase Order Incoterm')),
				'formatted_value'=>$object->get('Incoterm'),
				'label'=>ucfirst($object->get_field_label('Purchase Order Incoterm')),
				'required'=>false,
				'type'=>'value'


			),
			array(
				'id'=>'Purchase_Order_Port_of_Export',
				'edit'=>($edit?'string':''),
				'value'=>htmlspecialchars($object->get('Purchase Order Port of Export')),
				'formatted_value'=>$object->get('Port of Export'),
				'label'=>ucfirst($object->get_field_label('Purchase Order Port of Export')),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Purchase_Order_Port_of_Import',
				'edit'=>($edit?'string':''),
				'value'=>htmlspecialchars($object->get('Purchase Order Port of Import')),
				'formatted_value'=>$object->get('Port of Import'),
				'label'=>ucfirst($object->get_field_label('Purchase Order Port of Import')),
				'required'=>false,
				'type'=>'value'
			),




		)
	),

);


?>
