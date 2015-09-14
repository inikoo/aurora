<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 September 2015 23:43:24 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

function get_dashboard_navigation($data) {

	global $user,$smarty;



$left_buttons=array();

	$right_buttons=array();
	//$right_buttons[]=array('icon'=>'cog','title'=>_('Settings'),'url'=>'customer_store_configuration.php?store='.$store->id);
	//$right_buttons[]=array('icon'=>'edit','title'=>_('Edit customers'),'reference'=>'customers/'.$store->id.'/edit');
	//$right_buttons[]=array('icon'=>'plus','title'=>_('New customer'),'id'=>"new_customer");
	$sections=get_sections('dashboard');

	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;




	$_content=array(
		'sections_class'=>'',
		'sections'=>$sections,

		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>_('Dashboard').' <span class="id">'.'</span>',
		'search'=>array('show'=>true,'placeholder'=>_('Search'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


?>