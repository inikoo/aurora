<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 10 August 2016 at 13:08:13 GMT+8,  Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/




$data=array(
	'id'=>'Supplier_Default_PO_Terms_and_Conditions',

	'content'=>$state['_object']->get('Purchase Order Terms and Conditions'),

	'data'=>base64_encode(json_encode(array(
				'mode'=>'edit_object',
				'field'=>'Supplier_Default_PO_Terms_and_Conditions',
				'plugins'=>array('align', 'draggable', 'image', 'link', 'save', 'entities', 'emoticons', 'fullscreen', 'lineBreaker', 'table', 'codeView', 'codeBeautifier'),
				'metadata'=>array(
					'tipo'=>'edit_field',
					'object'=>'PurchaseOrder',
					'key'=>$state['_object']->id,
					'field'=>'Purchase Order Terms and Conditions',



				)
			)
		))

);


$smarty->assign('editor_data', $data);

$html=$smarty->fetch('editor.tpl');

?>
