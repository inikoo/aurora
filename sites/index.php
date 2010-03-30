<?php
include_once('common.php');
$css_files=array(
		 'css/common.css',
		 'css/home.css',
		 'css/dropdown.css'
		 );
$js_files=array('js/dropdown.js');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$banners=get_banners($store_code);
$smarty->assign('banners',$banners);
$showcases=get_showcases($store_code);
$smarty->assign('main_showcase',$showcases['main']['template']);
$smarty->assign('second_showcase',$showcases['second']['template']);


$smarty->assign('home_header_template',"pages/$store_code/home_header.tpl");
$smarty->assign('right_menu_template',"pages/$store_code/right_menu.tpl");
$smarty->assign('left_menu_template',"pages/$store_code/left_menu.tpl");




$sql=sprintf("select `Page Store Slogan`,`Page Store Title`,`Page Code`,`Page Title`,`Page Short Title`,`Page Store Subtitle`,`Page Store Resume`,`Page Source Template` from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`='home' ");

//print $sql;
$res=mysql_query($sql);
if(!$page_data=mysql_fetch_array($res)){
  header('Location: index.php');
  exit;
}

//print_r($page_data);


$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);

$smarty->assign('comentary',$page_data['Page Store Resume']);
$smarty->assign('contents',$page_data['Page Source Template']);

$smarty->display($page_data['Page Source Template']);

function get_banners($store_code){
$banners=array(
	       'top'=>array('url'=>'page.php?name=romance','src'=>'art/banners/love_time.gif')
	       ,'bottom'=>array('url'=>'page.php?name=twiter','src'=>'art/banners/follow_aw.gif')

	       );



return $banners;

}


function get_showcases($store_code){
$showcases=array(
	       'main'=>array('template'=>'splinters/showcases/'.$store_code.'/presentation.tpl')
	       ,'second'=>array('template'=>'splinters/showcases/'.$store_code.'/feedback.tpl')

	       );



return $showcases;

}


?>