<?php
include_once('common.php');
include_once('class.Page.php');

$css_files=array(
		 'css/common.css',
		 'css/home.css',
		 'css/info.css',
		 'css/dropdown.css'
		 );
$js_files=array('js/dropdown.js');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['page'])){
  $page=$_REQUEST['page'];
}else{
  header('Location: index.php?nopage');
  exit;
}



$page=new Page('store_page_code',$store->id,$page);


if(!$page->id){
  header('Location: index.php?page_not_found');
  exit;
}

$smarty->assign('home_header_template',"templates/home_header.".$store->data['Store Locale'].".tpl");
$smarty->assign('right_menu_template',"templates/right_menu.".$store->data['Store Locale'].".tpl");
$smarty->assign('left_menu_template',"templates/left_menu.".$store->data['Store Locale'].".tpl");

$smarty->assign('page',$page);

$smarty->assign('title',$page->data['Page Title']);
$smarty->assign('header_title',$page->data['Page Store Title']);
$smarty->assign('header_subtitle',$page->data['Page Store Subtitle']);
$smarty->assign('slogan',$page->data['Page Store Slogan']);

$smarty->assign('comentary',$page->data['Page Store Resume']);

$smarty->assign('contents',$page->data['Page Source Template']);


$smarty->assign('js_files',$js_files);
$smarty->display("templates/info.".$store->data['Store Locale'].".tpl");




?>