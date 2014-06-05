<?php
include_once 'class.DB_Table.php';

include_once 'class.Payment.php';

include_once 'common.php';
$page_key=$site->get_page_key_from_section('Thanks');


if (!isset($page_key)) {
	header('Location: index.php?no_page_key');
	exit;
}

$page=new Page($page_key);



if (!$page->id) {
	header('Location: index.php?no_page');
	exit;
}

$template_suffix='';
update_page_key_visit_log($page->id,$user_click_key);


$smarty->assign('logged',$logged_in);
$page->site=$site;
$page->user=$user;
$page->logged=$logged_in;
$page->currency=$store->data['Store Currency Code'];
$page->currency_symbol=currency_symbol($store->data['Store Currency Code']);
$page->customer=$customer;


$smarty->assign('title',$page->data['Page Title']);
$smarty->assign('store',$store);
$smarty->assign('page',$page);
$smarty->assign('site',$site);

$css_files=array();
$js_files=array();
// CSS JS FILES FOR ALL PAGES
$base_css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'editor/assets/skins/sam/editor.css',
	$yui_path.'assets/skins/sam/autocomplete.css'

);
$base_js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'editor/editor-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	$yui_path.'uploader/uploader-min.js',
	'external_libs/ampie/ampie/swfobject.js',
	'js/common.js',
	'js/edit_common.js',
);




$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Header External File Bridge` where `Page Header Key`=%d",$page->data['Page Header Key']);
$res=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$base_js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Footer External File Bridge` where `Page Footer Key`=%d",$page->data['Page Footer Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$base_js_files[]='public_external_file.php?id='.$row['external_file_key'];

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `Site Key`=%d",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$base_js_files[]='public_external_file.php?id='.$row['external_file_key'];

}



$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Store External File Bridge` where `Page Key`=%d",$page->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]='public_external_file.php?id='.$row['external_file_key'];
	else
		$base_js_files[]='public_external_file.php?id='.$row['external_file_key'];
}





$smarty->assign('type_content','file');

$css_files[]='css/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.css';

if ($page->data['Page Code']=='login') {

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== false) {
		$smarty->assign('template_string','login.chrome.tpl');
		$js_files[]='js/login.chrome.js';
	}else {
		$smarty->assign('template_string','login.tpl');
		$js_files[]='js/login.js';
	}
}else {

	$smarty->assign('template_string',$page->data['Page Store Content Template Filename'].$template_suffix.'.tpl');
	$js_files[]='js/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.js';
}






array_unshift($css_files,'css/order.css');


if (!$logged_in) {
	header('location: login.php');
	exit;
}


if (!isset($_REQUEST['id'])) {
	$smarty->assign('template_string','order_not_found.tpl');

}else {

	$order_key=$_REQUEST['id'];
	$order=new Order($order_key);

	if (!$order->id) {
		$smarty->assign('template_string','order_not_found.tpl');

	}else {





		$page->order=$order;

$payment=new Payment($order->data['Order Payment Key']);

include_once 'send_confirmation_email_function.php';
	send_confirmation_email($order);


		$smarty->assign('referral','');
		$smarty->assign('products_display_type','ordered');

		$js_files[]='js/table_common.js';
		$js_files[]='js/edit_common.js';

		array_unshift($css_files,'css/table.css');
		array_unshift($css_files,'css/edit.css');
		array_unshift($css_files,'css/inikoo.css');






		$smarty->assign('filter0','code');
		$smarty->assign('filter_value0','');
		$filter_menu=array(
			'code'=>array('db_key'=>'code','menu_label'=>'Code starting with  <i>x</i>','label'=>'Code'),
			'family'=>array('db_key'=>'family','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code'),
			'name'=>array('db_key'=>'name','menu_label'=>'Name starting with  <i>x</i>','label'=>'Code')

		);
		$smarty->assign('filter_menu0',$filter_menu);
		$smarty->assign('filter_name0',$filter_menu['code']['label']);


		$paginator_menu=array(10,25,50,100);
		$smarty->assign('paginator_menu0',$paginator_menu);



		$smarty->assign('order',$order);
		$smarty->assign('customer',$customer);



		$charges_deal_info=$order_in_process->get_no_product_deal_info('Charges');
		if ($charges_deal_info!='') {
			$charges_deal_info='<span style="color:red" title="'.$charges_deal_info.'">*</span> ';
		}
		$smarty->assign('charges_deal_info',$charges_deal_info);
	}
}




$smarty->assign('css_files',array_merge( $base_css_files,$css_files));
$smarty->assign('js_files',array_merge( $base_js_files,$js_files));

$smarty->display('page.tpl');

?>
