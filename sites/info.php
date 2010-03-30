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

if(isset($_REQUEST['page'])){
  $page=$_REQUEST['page'];
}else{
  header('Location: index.php');
  exit;
}

$sql=sprintf("select `Page Store Slogan`,`Page Store Title`,`Page Code`,`Page Title`,`Page Short Title`,`Page Store Subtitle`,`Page Store Abstract`,`Page Source Template` from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`=%s ",prepare_mysql($page));

$res=mysql_query($sql);
if(!$page_data=mysql_fetch_array($res)){
  header('Location: index.php');
  exit;
}

$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);

$smarty->assign('comentary',$page_data['Page Store Abstract']);
$smarty->assign('contents',$page_data['Page Source Template']);


$smarty->assign('js_files',$js_files);
$smarty->display('info.tpl');




?>