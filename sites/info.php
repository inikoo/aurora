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
  $page_code=$_REQUEST['page'];

}else{
  header('Location: index.php?nopage');
  exit;
}



$page=new Page('store_page_code',$store->id,$page_code);


if(!$page->id){
  header('Location: index.php?page_not_found');
  exit;
}

$smarty->assign('home_header_template',"templates/home_header.".$store->data['Store Locale'].".tpl");
$smarty->assign('right_menu_template',"templates/right_menu.".$store->data['Store Locale'].".tpl");
$smarty->assign('left_menu_template',"templates/left_menu.".$store->data['Store Locale'].".tpl");

$smarty->assign('page',$page);
$page_data=$page->get_data_for_smarty($page_data);

$smarty->assign('page_data',$page_data);

$smarty->assign('contents',$page->data['Page Source Template']);


$smarty->assign('js_files',$js_files);
$smarty->display("templates/info.".$store->data['Store Locale'].".tpl");


update_page_key_visit_log($page->data['Page Key']);

$_SESSION['prev_page_key']=$page->data['Page Key'];


?>
