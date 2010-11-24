<?php
include_once('common.php');
include_once('class.Store.php');
include_once('class.Page.php');

$css_files=array(

		 'css/common.css',
		 'css/home.css',
		 'css/info.css',
		 'css/register.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		'http://yui.yahooapis.com/combo?2.8.0r4/build/utilities/utilities.js&2.8.0r4/build/json/json-min.js'
	       
		,'js/md5.js'
		,'js/sha256.js'
		
		,'js/dropdown.js'
);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['we'])){
  $smarty->assign('error',true);

}

$store=new Store($store_key);
$smarty->assign('store',$store);


$page=new Page('store_page_code',$store_key,'register');

if(!$page->id){
  header('Location: index.php');
}

$smarty->assign('home_header_template' , "templates/home_header.".$store->data['Store Locale'].".tpl" );
$smarty->assign('right_menu_template'  , "templates/right_menu.".$store->data['Store Locale'].".tpl"  );
$smarty->assign('left_menu_template'   , "templates/left_menu.".$store->data['Store Locale'].".tpl"   );





$smarty->assign('title',$page->data['Page Title']);
$smarty->assign('header_title',$page->data['Page Store Title']);
$smarty->assign('header_subtitle',$page->data['Page Store Subtitle']);
$smarty->assign('slogan',$page->data['Page Store Slogan']);

$smarty->assign('comentary',$page->data['Page Store Resume']);

update_page_key_visit_log($page->data['Page Key']);

$_SESSION['prev_page_key']=$page->data['Page Key'];

$options=$page->get_options();
print_r($options);
if($options['Form_Type']=='Steps')
{
$js_files[]='js/register.js.php';
$template="templates/register.".$store->data['Store Locale'].".tpl";
}
else {
$js_files[]='js/register_2.js.php';
$template="templates/register_2.".$store->data['Store Locale'].".tpl";

}

$smarty->assign('js_files',$js_files);
$smarty->display($template);




?>
