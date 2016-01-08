<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 September 2015 13:44:31 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

include_once 'utils/invalid_messages.php';

$store=$state['_object'];


$options_Store_Locale=array(
	'en_GB'=>'en_GB '._('British English'),
	'de_DE'=>'de_DE'._('German'),
	'fr_FR'=>'fr_FR'._('French'),
	'es_ES'=>'es_ES'._('Spanish'),
	'pl_PL'=>'pl_PL'._('Polish'),
	'it_IT'=>'it_IT'._('Italian'),
	'sk_SK'=>'sk_SK'._('Sloavak'),
	'pt_PT'=>'pt_PT'._('Portuguese'),
);
asort($options_Store_Locale);


$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'render'=>false,
				'class'=>'locked',
				'id'=>'Store_Key',
				'value'=>$store->id  ,
				'label'=>_('Id')
			),
			array(
				'edit'=>'string',
				'id'=>'Store_Code',
				'value'=>$store->get('Store Code')  ,
				'label'=>ucfirst($store->get_field_label('Store Code')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
				'server_validation'=>'check_for_duplicates',




			),
			array(
				'edit'=>'string',
				'id'=>'Store_Name',
				'value'=>$store->get('Store Name'),
				'label'=>ucfirst($store->get_field_label('Store Name')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>true,
			),


		)
	),
	array(
		'label'=>_('Localization'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Store_Locale',
				'value'=>$store->get('Store Locale') ,
				'label'=>_('Country')
			),
			array(
				'id'=>'Store_Currency_Code',
				'value'=>$store->get('Store Currency Code') ,
				'label'=>_('Currency')
			),
			array(
				'id'=>'Store_Timezone',
				'value'=>$store->get('Store Timezone') ,
				'label'=>_('Timezone')
			)

		)
	),
	array(
		'label'=>_('Contact'),
		'show_title'=>true,
		'fields'=>array(

			array(
				'edit'=>'email',
				'id'=>'Store_Email',
				'value'=>$store->get('Store Email')  ,
				'label'=>ucfirst($store->get_field_label('Store Email')),
				'invalid_msg'=>get_invalid_message('email'),
				'required'=>false,




			),
			array(
				'edit'=>'telephone',
				'id'=>'Store_Telephone',
				'value'=>$store->get('Store Telephone'),
				'formated_value'=>$store->get('Telephone'),
				'label'=>ucfirst($store->get_field_label('Store Telephone')),
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
			),

			array(
				'edit'=>'textarea',
				'id'=>'Store_Address',
				'value'=>$store->get('Store Address'),
				'formated_value'=>$store->get('Address'),
				'label'=>ucfirst($store->get_field_label('Store Address')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
			),
			array(
				'edit'=>'string',
				'id'=>'Store_URL',
				'value'=>$store->get('Store URL'),
				'formated_value'=>$store->get('Store URL'),
				'label'=>ucfirst($store->get_field_label('Store URL')),
				'invalid_msg'=>get_invalid_message('string'),
				'required'=>false,
			),

		)
	)

);
$smarty->assign('object_fields', $object_fields);
$smarty->assign('state', $state);

$html=$smarty->fetch('object_fields.tpl');

?>
