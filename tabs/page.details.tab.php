<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 October 2015 at 10:56:20 BST, Sheffield UK
 Copyright (c) 2015, Inikoo

 Version 3

*/

$website=new Page($state['key']);



$object_fields=array(
	array(
		'label'=>_('Id'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'class'=>'locked',
				'id'=>'Page_Key',
				'value'=>$website->get('Page Key') ,
				'label'=>_('Id')
			),

			array(
				'class'=>'string',
				'id'=>'Page_Code',
				'value'=>$website->get('Page Code'),
				'label'=>_('Code')
			),
			array(
				'class'=>'string',
				'id'=>'Page_Name',
				'value'=>$website->get('Page Name'),
				'label'=>_('Name')
			),
			array(
				'class'=>'string',
				'id'=>'Page_URL',
				'value'=>$website->get('Page URL'),
				'label'=>'URL'
			)

		)
	),
	array(
		'label'=>_('Header'),
		'show_title'=>false,
		'fields'=>array(
			

			array(
				'class'=>'string',
				'id'=>'Page_Store_Title',
				'value'=>$website->get('Page Store Title'),
				'label'=>_('Title')
			)
		)
	),
	
);
$smarty->assign('object_fields',$object_fields);

$html=$smarty->fetch('edit_object.tpl');

?>
