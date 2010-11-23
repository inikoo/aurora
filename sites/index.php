<?php

include_once('common.php');
$css_files=array(
		 'css/common.css',
		 
		 'css/home.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		'http://yui.yahooapis.com/combo?2.8.1/build/utilities/utilities.js&2.8.1/build/datasource/datasource-min.js&2.8.1/build/autocomplete/autocomplete-min.js&2.8.1/build/json/json-min.js',
		'js/sha256.js',
		'js/aes.js',
		'js/login.js',
		'js/search.js',
		'js/dropdown.js

');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$banners=get_banners($store_code);
$smarty->assign('banners',$banners);
$showcases=get_showcases($store_code);
$smarty->assign('main_showcase',$showcases['main']['template']);
//$smarty->assign('second_showcase',$showcases['second']['template']);


$smarty->assign('home_header_template',"templates/home_header.".$store->data['Store Locale'].".tpl");
$smarty->assign('right_menu_template',"templates/right_menu.".$store->data['Store Locale'].".tpl");
$smarty->assign('left_menu_template',"templates/left_menu.".$store->data['Store Locale'].".tpl");




$sql=sprintf("select `Page Store Slogan`,`Page Store Title`,`Page Code`,`Page Title`,`Page Short Title`,`Page Store Subtitle`,`Page Store Resume`,`Page Source Template` from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`='home' ");

//print $sql;
$res=mysql_query($sql);

if(!$page_data=mysql_fetch_array($res)){
  
  exit();
}

//print_r($page_data);


$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);

$smarty->assign('comentary',$page_data['Page Store Resume']);
$smarty->assign('contents',$page_data['Page Source Template']);


$smarty->display('templates/home.'.$store->data['Store Locale'].'.tpl');
//exit(getcwd());
//$smarty->display('templates/x.tpl');

function get_banners($store_code){
$banners=array(
	       'top'=>array('url'=>'page.php?name=romance','src'=>'art/banners/love_time.gif')
	       ,'bottom'=>array('url'=>'page.php?name=twiter','src'=>'art/banners/follow_aw.gif')

	       );



return $banners;

}


function get_showcases($store_code){
$showcases=array(
	       'main'=>array('template'=>'showcases/presentation.html')
	       //	       ,'second'=>array('template'=>'splinters/showcases/feedback.tpl')

	       );



return $showcases;

}
//=====================================================================
$page_data=$store->get_page_data();
update_page_key_visit_log($page_data['Page Key']);

$_SESSION['prev_page_key']=$page_data['Page Key'];
//========================================================================

?>
