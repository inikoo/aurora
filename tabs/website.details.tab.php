<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 20 September 2015 13:22:27 GMT+8, Kuala Lumpur
 Copyright (c) 2015, Inikoo

 Version 3

*/

$website=new Site($state['key']);



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'Site_Key',
				'value'=>$website->get('Site Key') ,
				'label'=>_('Id')
			),

			array(
				'class'=>'string',
				'id'=>'Site_Code',
				'value'=>$website->get('Site Code'),
				'label'=>_('Code')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Nme',
				'value'=>$website->get('Site Name'),
				'label'=>_('Nme')
			),

		)
	),
	array(
		'label'=>_('Contact'),
		'show_title'=>false,
		'fields'=>array(
			

			array(
				'class'=>'string',
				'id'=>'Site_Main_Contact_Name',
				'value'=>$website->get('Site Main Contact Name'),
				'label'=>_('Contact name')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Main_Plain_Email',
				'value'=>$website->get('Site Main XHTML Email'),
				'label'=>_('Email')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Main_Plain_Telephone',
				'value'=>$website->get('Site Main Plain Telephone'),
				'label'=>_('Phone')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Main_Plain_Mobile',
				'value'=>$website->get('Site Main Plain Mobile'),
				'label'=>_('Mobile')
			),
			array(
				'class'=>'string',
				'id'=>'Site_Main_Plain_FAX',
				'value'=>$website->get('Site Main Plain FAX'),
				'label'=>_('FAX')
			),
			array(
				'class'=>'address',
				'id'=>'Site_Main_Plain_Adresss',
				'value'=>$website->get('Site Main XHTML Address'),
				'label'=>_('Address')
			)
		)
	),
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('object_fields.tpl');

?>
