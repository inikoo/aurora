<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 4 November 2015 at 21:11:20 CET Tessera Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

$payment=$state['_object'];

$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			
			
			array(
				'class'=>'string',
				'id'=>'Payment_Key',
				'value'=>$payment->get('Payment Key'),
				'label'=>_('Id')
			),
			array(
				'class'=>'string',
				'id'=>'Payment_Transaction_ID',
				'value'=>$payment->get('Payment Transaction ID'),
				'label'=>_('Reference')
			),
		

		)
	),
array(
		'label'=>_('Amount'),
		'show_title'=>true,
		'fields'=>array(
			
			
	
			array(
				'class'=>'string',
				'id'=>'Payment_Currency_Code',
				'value'=>$payment->get('Payment Currency Code'),
				'label'=>_('Currency')
			),
			array(
				'class'=>'string',
				'id'=>'Payment_Amount',
				'value'=>$payment->get('Amount'),
				'label'=>_('Amount')
			),

		)
	),

	
	
);
$smarty->assign('object_fields',$object_fields);
$smarty->assign('state', $state);

$html=$smarty->fetch('object_fields.tpl');

?>
