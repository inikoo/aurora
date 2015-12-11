<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 October 2015 at 12:45:38 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_utils_navigation($data) {
	global $smarty;
	$branch=array(array('label'=>'', 'icon'=>'home', 'reference'=>''));


	if ($data['section']=='not_found') {

		switch ($data['parent']) {
		case 'store':
			$title=_('Store not found');
			break;
		case 'customer':
			$title=_('Customer not found');
			break;
		case 'warehouse':
			$title=_('Warehouse not found');
			break;
		case 'supplier':
			$title=_('Supplier not found');
			break;
		case 'employee':
			$title=_('Employee not found');
			break;
		case 'user':
			$title=_('User not found');
			break;
		case 'order':
			$title=_('Order not found');
			break;
		case 'invoice':
			$title=_('Invoice not found');
			break;
		case 'delivery_note':
			$title=_('Delivery note not found');
			break;
		default:
			$title=_('Not found');
			break;
		}


	}else if ($data['section']=='forbidden') {
		$title=_('Forbidden');
	}else {
		$title='';
	}
	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>array(),
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>$title,
		'search'=>array('show'=>false, 'placeholder'=>'')

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}



function get_fire_navigation($data) {
	global $smarty;
	$branch=array(array('label'=>'', 'icon'=>'home', 'reference'=>''));



	$_content=array(
		'branch'=>$branch,
		'sections_class'=>'',
		'sections'=>array(),
		'left_buttons'=>array(),
		'right_buttons'=>array(),
		'title'=>_('Fire evacuation roll call'),
		'search'=>array('show'=>false, 'placeholder'=>'')

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;
}
?>
