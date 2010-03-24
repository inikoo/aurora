<?php
include_once('common.php');
$css_files=array(
		 'css/common.css',
		 'css/home.css',
		 'css/info.css',
		 'css/dropdown.css'
		 );
$js_files=array('js/dropdown.js');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['code'])){
  $department=new Department('code',$_REQUEST['code']);
  if(!$department->id){
    header('Location: cataloge.php');
    exit;
  }

}else{
  header('Location: cataloge.php');
  exit;
}

$page_data=$department->get_page_data();



$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);

$smarty->assign('comentary',$page_data['Page Store Abstract']);
$smarty->assign('contents',$page_data['Page Source Template']);


$smarty->assign('js_files',$js_files);
$smarty->display('department.tpl');




?>