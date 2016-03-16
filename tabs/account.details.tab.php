<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2015 at 13:25:54 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

global $account;


$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			
			
			
			array(
			
				
				'id'=>'Account_Name',
				'edit'=>'string',
				'value'=>htmlspecialchars($account->get('Account Name')),
				'formatted_value'=>$account->get('Name'),
				'label'=>ucfirst($account->get_field_label('Account Name')),
				'required'=>false
				
				
			),

		)
	),
	array(
		'label'=>_('Localization'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Account_Country',
				'value'=>$account->get('Account Country Code') ,
				'label'=>_('Country')
			),
			array(
				'id'=>'Account_Currency',
				'value'=>$account->get('Account Currency') ,
				'label'=>_('Currency')
			),
			array(
				'id'=>'Account_Timezone',
				'value'=>$account->get('Account Timezone') ,
				'label'=>_('Timezone')
			)

		)
	),
	
	
	
);

$smarty->assign('state', $state);
$smarty->assign('object_fields', $object_fields);


$html=$smarty->fetch('edit_object.tpl');

?>
