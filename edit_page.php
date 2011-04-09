<?php
include_once('common.php');
include_once('class.Page.php');
include_once('class.Node.php');


if(isset($_REQUEST['id']) and is_numeric($_REQUEST['id'])){
  $page_id=$_REQUEST['id'];
  $_SESSION['state']['page']['id']=$page_id;
 
}elseif($_SESSION['state']['page']['id']){
    $page_id=$_SESSION['state']['page']['id'];
}else{
  exit('page ID not specified');
}
$page= new Page($page_id);
if(!$page->id)
  exit('Error page not found');

$smarty->assign('options',$page->get_options());

$smarty->assign('page',$page);
$smarty->assign('edit',$_SESSION['state']['page']['edit']);

$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'page.php','label'=>_('Exit Edit'));
$smarty->assign('general_options_list',$general_options_list);





$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
		 $yui_path.'container/assets/skins/sam/container.css',
		 $yui_path.'editor/assets/skins/sam/editor.css',
		  'text_editor.css',
		 'common.css',
		 'button.css',
		 'table.css',
		 'css/edit.css'
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
		$yui_path.'editor/editor-min.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/search.js',
		'js/table_common.js',
		'js/edit_common.js',
		'edit_page.js.php?page_id='.$page_id,
		
		);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('parent','products');
$smarty->assign('title','Editing Page:'.$page->get('Page Short Title'));
$smarty->display('edit_page.tpl');
?>