<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 16 December 2015 at 23:02:14 CET , Barcelona Airport , Spain

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_dashboard_navigation($data) {

	global $user, $smarty;


	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('production', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Manufacture dashbard'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search production'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_manufacture_tasks_navigation($data) {

	global $user, $smarty;


	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('production', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Manufacture Tasks'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search production'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_operatives_navigation($data) {

	global $user, $smarty;


	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('production', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Operatives'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search production'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_batches_navigation($data) {

	global $user, $smarty;


	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('production', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Batches'),
		'search'=>array('show'=>true, 'placeholder'=>_('Search production'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}





?>
