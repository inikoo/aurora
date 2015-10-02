<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 29 August 2015 13:51:15 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_websites_navigation($data) {

	global $user, $smarty;
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
		'search'=>array('show'=>true, 'placeholder'=>_('Search websites'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}


function get_website_navigation($data) {

	global $user, $smarty;
	require_once 'class.Site.php';



	$website=new Site($data['key']);

	$block_view=$data['section'];


	$sections_class='';
	$title=_('Website').' <span class="id">'.$website->get('Site Code').'</span>';

	$left_buttons=array();
	$right_buttons=array();


if ($user->websites>1) {




		list($prev_key,$next_key)=get_prev_next($website->id,$user->websites);
		$sql=sprintf("select `Site Code` from `Site Dimension` where `Site Key`=%d",$prev_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_title=_('Website').' '.$row['Site Code'];
		}else {$prev_title='';}
		$sql=sprintf("select `Site Code` from `Site Dimension` where `Site Key`=%d",$next_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$next_title=_('Website').' '.$row['Site Code'];
		}else {$next_title='';}


		$left_buttons[]=array('icon'=>'arrow-left','title'=>$prev_title,'reference'=>'website/'.$prev_key );
		$left_buttons[]=array('icon'=>'arrow-up','title'=>_('Websites'),'reference'=>'websites','parent'=>'');

		$left_buttons[]=array('icon'=>'arrow-right','title'=>$next_title,'reference'=>'website/'.$next_key );
	}


	$website=new Site($data['key']);
	$sections=get_sections('websites', $website->id);
	if (isset($sections[$data['section']]) )$sections[$data['section']]['selected']=true;


	$_content=array(
		'sections_class'=>$sections_class,
		'sections'=>$sections,
		'left_buttons'=>$left_buttons,
		'right_buttons'=>$right_buttons,
		'title'=>$title,
		'search'=>array('show'=>true, 'placeholder'=>_('Search website'))

	);
	$smarty->assign('_content', $_content);

	$html=$smarty->fetch('navigation.tpl');
	return $html;

}



?>
