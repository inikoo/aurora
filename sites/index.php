<?php

include_once('common.php');
$css_files=array(
		 'css/common.css',
		 
		 'css/home.css',
		 'css/dropdown.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-debug.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		'js/sha256.js',
		'js/aes.js',
		'js/login.js',
		'js/search.js',
		'js/dropdown.js'
);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->assign('home_header_template',"templates/home_header.".$store->data['Store Locale'].".tpl");
$smarty->assign('right_menu_template',"templates/right_menu.".$store->data['Store Locale'].".tpl");
$smarty->assign('left_menu_template',"templates/left_menu.".$store->data['Store Locale'].".tpl");

$page=$site->get_page_object('index');
if(!$page->id){
exit('no page');
}

$page_data=$page->get_data_for_smarty($page_data);
//print_r($page_data);

$smarty->assign('page_data',$page_data);

$smarty->display('templates/home.'.$store->data['Store Locale'].'.tpl');



update_page_key_visit_log($page->id);
$_SESSION['prev_page_key']=$page->id;


?>
