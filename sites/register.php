<?php
include_once('common.php');
$css_files=array(
		 'css/common.css',
		 'css/home.css',
		 'css/info.css',
		 'css/register.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		'http://yui.yahooapis.com/combo?2.8.0r4/build/utilities/utilities.js&2.8.0r4/build/connection/connection_core-min.js'
		,'js/register.js.php'
		,'js/dropdown.js'
);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$sql=sprintf("select `Page Store Slogan`,`Page Store Title`,`Page Code`,`Page Title`,`Page Short Title`,`Page Store Subtitle`,`Page Store Resume`,`Page Source Template` from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`='register' ");

$res=mysql_query($sql);
if(!$page_data=mysql_fetch_array($res)){
  header('Location: index.php');
  exit;
}

$smarty->assign('home_header_template',"pages/$store_code/home_header.tpl");
$smarty->assign('right_menu_template',"pages/$store_code/right_menu.tpl");
$smarty->assign('left_menu_template',"pages/$store_code/left_menu.tpl");


$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);

$smarty->assign('comentary',$page_data['Page Store Resume']);



$smarty->assign('js_files',$js_files);
$smarty->display($page_data['Page Source Template']);




?>