<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2015 at 13:25:54 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

global $inikoo_account;


$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			
			
			array(
				'class'=>'string',
				'id'=>'Account_Code',
				'value'=>$inikoo_account->get('Account Code'),
				'label'=>_('Code')
			),
			array(
				'class'=>'string',
				'id'=>'Account_Name',
				'value'=>$inikoo_account->get('Account Name'),
				'label'=>_('Name')
			),

		)
	),
	array(
		'label'=>_('Localization'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Account_Country',
				'value'=>$inikoo_account->get('Account Country Code') ,
				'label'=>_('Country')
			),
			array(
				'id'=>'Account_Currency',
				'value'=>$inikoo_account->get('Account Currency') ,
				'label'=>_('Currency')
			),
			array(
				'id'=>'Account_Timezone',
				'value'=>$inikoo_account->get('Account Timezone') ,
				'label'=>_('Timezone')
			)

		)
	),
	array(
		'label'=>_('Usage'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Account_Stores',
				'value'=>$inikoo_account->get('Stores') ,
				'label'=>_('Stores')
			),
          array(
				'id'=>'Account_Websites',
				'value'=>$inikoo_account->get('Websites') ,
				'label'=>_('Websites')
			),

		)
	),
	
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
