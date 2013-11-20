<?php
/*

 About:
 Autor: Raul Perusquia <rail@inikoo.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'common.php';
include_once 'class.Site.php';
include_once 'class.Page.php';
include_once 'class.PageHeader.php';

include_once 'class.PageFooter.php';

include_once 'class.Store.php';

if (!$user->can_view('sites') or !$user->can_edit('sites')  ) {
	header('Location: index.php?forbidden');
	exit;
}



if (!isset($_REQUEST['id'])){
header('Location: index.php?no_id');
	exit;

}else{
$splinter_key=$_REQUEST['id'];
}
if (!isset($_REQUEST['type']) or !in_array($_REQUEST['type'],array('header','footer'))){
header('Location: index.php?wrong_page_splinter_type');
	exit;

}else{
$splinter_type=$_REQUEST['type'];

}
if (!isset($_REQUEST['referral']) or !in_array($_REQUEST['referral'],array('site','page'))){
header('Location: index.php?no_referral_type');
	exit;

}else{
$referral=$_REQUEST['referral'];
}

if (!isset($_REQUEST['referral_key']) or !is_numeric($_REQUEST['referral_key']) ){
header('Location: index.php?no_referral_key');
	exit;

}else{
$referral_key=$_REQUEST['referral_key'];

}



if($splinter_type=='header'){
$splinter=new PageHeader($splinter_key);
}else{
$splinter=new PageFooter($splinter_key);

}


if(!$splinter->id){


header('Location: index.php?no_splinter_found');
	exit;
}



if($referral=='site'){
$referral=new Site($referral_key);
}elseif($referral=='page'){
$referral=new Page($referral_key);

}

$site=new Site($referral->get_site_key());
$store=new Store($site->data['Site Store Key']);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);
$smarty->assign('site',$site);
$smarty->assign('splinter',$splinter);



if ( !$referral->id or   !in_array($referral->get_site_key(),$user->websites   ) ) {
	header('Location: index.php?no_permision');
	exit;
}





$smarty->assign('referral',$referral);



$smarty->assign('search_label',_('Website'));
$smarty->assign('search_scope','site');


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'theme.css.php'

);
$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'js/edit_common.js',
	'edit_page_splinter.js.php'
);

$edit_block='description';
$smarty->assign('edit_block',$edit_block);



$smarty->assign('parent','sites');

if($splinter_type=='header'){
$smarty->assign('title',_('Editing Header'));


}else{
$smarty->assign('title',_('Editing Footer'));
}



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display('edit_page_splinter.tpl');
?>
