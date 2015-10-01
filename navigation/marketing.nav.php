<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 16:42:35 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_deals_navigation($data) {

	global $user,$smarty;

	$block_view=$data['section'];

	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('marketing','');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Deals'),
		'search'=>array('show'=>true,'placeholder'=>_('Search marketing'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



?>
