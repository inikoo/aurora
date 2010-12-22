<?php
include_once('common.php');
include_once('class.Store.php');

$css_files=array(

		 'css/common.css',
		 'css/home.css',
		 'css/info.css',
		 'css/register.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-debug.js', 
		'js/sha256.js,',
		'js/aes.js',
		'js/login.js',
	'js/dropdown.js'
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


$smarty->assign('home_header_template',"templates/home_header.".$store->data['Store Locale'].".tpl");
$smarty->assign('right_menu_template',"templates/right_menu.".$store->data['Store Locale'].".tpl");
$smarty->assign('left_menu_template',"templates/left_menu.".$store->data['Store Locale'].".tpl");


$smarty->assign('page',$page);
$page_data=$page->get_data_for_smarty($page_data);
$smarty->assign('page_data',$page_data);

print_r($page_data);


$smarty->assign('js_files',$js_files);



update_page_key_visit_log($page->id);
$_SESSION['prev_page_key']=$page->id;

$smarty->display("templates/login.".$store->data['Store Locale'].".tpl");

?>
