<?php
include_once('common.php');
include_once('class.Order.php');
include_once('class.Customer.php');


$yui_path="../external_libs/yui/2.8.0r4/build/";

$css_files=array(
		 'css/common.css',
		 'css/family.css',
		 'css/dropdown.css',
		// 'http://yui.yahooapis.com/combo?2.8.0r4/build/paginator/assets/skins/sam/paginator.css&2.8.0r4/build/datatable/assets/skins/sam/datatable.css',
		 'css/table.css',
		 'css/thumbnail.css',
		 'css/order.css'
		 
		 );
$js_files=array(
		//'http://yui.yahooapis.com/combo?2.8.0r4/build/utilities/utilities.js&2.8.0r4/build/paginator/paginator-min.js&2.8.0r4/build/datasource/datasource-min.js&2.8.0r4/build/datatable/datatable-min.js&2.8.0r4/build/json/json-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable-debug.js',
		$yui_path.'container/container_core-min.js',
		$yui_path.'menu/menu-min.js',
		'js/dropdown.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/edit_common.js',
		'js/checkout.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('js_files',$js_files);

$sql=sprintf("select P.`Page Key`,`Page Store Slogan`,`Page Store Title`,`Page Code`,`Page Title`,`Page Short Title`,`Page Store Subtitle`,`Page Store Resume`,`Page Source Template` from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`='checkout' and `Page Parent Key`=%d ",$store_key);
$res=mysql_query($sql);
if(!$page_data=mysql_fetch_array($res)){
  header('Location: index.php?e');
  exit;
}
if(!$logged_in){
  header('Location: login.php?fp='.$page_data['Page Key']);
  exit;
}


$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);
$smarty->assign('page_key',$page_data['Page Key']);

$smarty->assign('comentary',$page_data['Page Store Resume']);
$smarty->assign('contents',$page_data['Page Source Template']);
$smarty->assign('header_template',"family_header.tpl");
$smarty->assign('right_menu_template',"right_menu.tpl");
$smarty->assign('left_menu_template',"left_menu.tpl");

$order=new Order($_SESSION['order_key']);
$smarty->assign('order',$order);
$customer=new Customer($_SESSION['customer_key']);
$smarty->assign('customer',$customer);
$smarty->assign("header_tpl","templates/checkout_header.".$store->data['Store Locale'].".tpl");

$smarty->display("templates/checkout.".$store->data['Store Locale'].".tpl");



?>