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

$banners=get_banners();
$smarty->assign('banners',$banners);
$showcases=get_showcases();
$smarty->assign('main_showcase',$showcases['main']['template']);
$smarty->assign('second_showcase',$showcases['second']['template']);


$sql=sprintf("select `Product Department Code`,`Product Department Name` from `Product Department Dimension` where `Product Department Store Key`=%d",$store_key);
$res=mysql_query($sql);
$departments=array();
while($row=mysql_fetch_array($res)){
  $departments[]=array('code'=>$row['Product Department Code'],'name'=>$row['Product Department Name']);
}

$smarty->assign('departments',$departments);


$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('comentary',$page_data['Page Store Abstract']);
$smarty->assign('contents',$page_data['Page Source Template']);



$smarty->display('home.tpl');

function get_banners(){
$banners=array(
	       'top'=>array('url'=>'page.php?name=romance','src'=>'art/banners/love_time.gif')
	       ,'bottom'=>array('url'=>'page.php?name=twiter','src'=>'art/banners/follow_aw.gif')

	       );



return $banners;

}


function get_showcases(){
$showcases=array(
	       'main'=>array('template'=>'splinters/showcases/presentation.tpl')
	       ,'second'=>array('template'=>'splinters/showcases/feedback.tpl')

	       );



return $showcases;

}


?>