<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 1 October 2015 at 18:17:55 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

function get_performance_navigation($data) {

	global $user, $smarty;

	$block_view=$data['section'];

	$left_buttons=array();
	$right_buttons=array();
	$sections=get_sections('reports', '');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;



	switch ($data['tab']) {
	case ('report.pp'):
		$title='Pickers & Packers Report';
		break;

	case ('report.outofstock'):
		$title='Out of Stock';
		break;
	case ('report.top_customers'):
		$title='Top Customers';
		break;
	case ('report.top_customers'):
		$title='Top Customers';
		break;
	default:
		$title='';
	}


	$_content=array(

		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search reports'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



?>
