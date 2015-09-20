<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2015 13:51:15 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_websites_navigation($data) {

	global $user,$smarty;
	require_once 'class.Store.php';


	$block_view=$data['section'];


	$sections_class='';
	$title=_('Websites');

	$left_buttons=array();



	$right_buttons=array();


	$sections=get_sections('websites');
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search websites'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}

function get_website_navigation($data) {

	global $user,$smarty;
	require_once 'class.Site.php';

    $site=new Site($data['parent_key']);

	$block_view=$data['section'];


	$sections_class='';
	$title=_('Website');

	$left_buttons=array();



	$right_buttons=array();

$website=new Site($data['key']);
	$sections=get_sections('websites',$website->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true,'placeholder'=>_('Search website'))

	);
	$smarty->assign('_content',$_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



?>
