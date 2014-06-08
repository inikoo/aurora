<?php
include_once 'common.php';
$page_key=$site->get_profile_page_key();


$page=new Page($page_key);
$title=$page->data['Page Title'];


if (!$page->id) {
	header('Location: index.php?no_page');
	exit;
}


if ($page->data['Page Site Key']!=$site->id) {
	header('Location: index.php?site_page_not_match');
	//    exit("No site/page not match");
	exit;
}

if (!$logged_in) {
	header('location: login.php');
	exit;
}



if (isset($_REQUEST['view']) and
	in_array($_REQUEST['view'],array('contact','orders','change_password','delivery_addresses','billing_addresses','order'))) {
	$view=$_REQUEST['view'];
} else {
	$view='contact';
}



$template_suffix='_'.$view;

update_page_key_visit_log($page->id,$user_click_key);


$page->customer=$customer;
$page->order=$order_in_process;


$smarty->assign('logged',$logged_in);
$page->site=$site;
$page->user=$user;
$page->logged=$logged_in;
$page->currency=$store->data['Store Currency Code'];
$page->currency_symbol=currency_symbol($store->data['Store Currency Code']);
$page->customer=$customer;


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
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/container.css',
	'css/edit.css',
	'css/inikoo.css',
	'css/profile.css'


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
	'js/edit_currency.js',

	'js/common.js',
	'js/edit_common.js',


);



$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Header External File Bridge` where `Page Header Key`=%d",$page->data['Page Header Key']);
$res=mysql_query($sql);
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
$smarty->assign('template_string',$page->data['Page Store Content Template Filename'].$template_suffix.'.tpl');
$js_files[]='js/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.js';

$smarty->assign('user',$user);
$smarty->assign('view',$view);
$smarty->assign('default_country_2alpha',$_SESSION['ip_country_2alpha_code']);


if ($view=='contact') {

	$js_files[]='js/country_address_labels.js';

	$js_files[]='js/edit_address.js';
	$js_files[]='js/common_check_tax_number.js';

	$js_files[]='profile_contact.js.php';


	$custom_fields=array();
	$sql=sprintf("select * from `Custom Field Dimension`  where `Custom Field Table`='Customer' and `Custom Field In Profile`='Yes'  ");
	$result=mysql_query($sql);
	mysql_fetch_assoc($result);
	while ($row=mysql_fetch_assoc($result)) {
		$sql=sprintf("select * from `Customer Custom Field Dimension` where `Customer Key`=%d", $customer->id);

		$res=mysql_query($sql);
		$r=mysql_fetch_assoc($res);
		$val=$r[$row['Field']];


		$sql=sprintf("select * from `Custom Field Dimension` where `Custom Field Key`=%d", $row['Field']);
		$res=mysql_query($sql);
		$r=mysql_fetch_assoc($res);

		$custom_fields[]=array('name'=>$r['Custom Field Name'], 'value'=>$val, 'type'=>$r['Custom Field Type']);
	}

	$smarty->assign('custom_fields',$custom_fields);



	$categories=array();
	$categories_value=array();
	$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Customer' and `Category Deep`=1 and `Category Store Key`=%d and `Category Show Public Edit`='Yes'",$customer->data['Customer Store Key']);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$tmp=new Category($row['Category Key']);
		$selected_array=$tmp->sub_category_selected_by_subject($customer->id);


		if (count($selected_array)==0) {
			$tmp_selected='';
		} else {
			$tmp_selected=array_pop($selected_array);
		}

		$categories[$row['Category Key']]=$tmp;
		$categories_value[$row['Category Key']]=$tmp_selected;

	}


	$smarty->assign('categories',$categories);
	$smarty->assign('categories_value',$categories_value);

	$enable_other=array();

	$other_value=array();
	foreach ($categories_value as $key=>$value) {
		$category=new Category($value);

		if ($category->data['Is Category Field Other'] == 'Yes') {

			$sql=sprintf("select * from `Category Bridge` where `Category Key`=%d and `Subject`='Customer' and `Subject Key`=%d", $category->id, $customer->id);
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			$enable_other[$category->data['Category Parent Key']]=true;
			$other_value[$category->data['Category Parent Key']]=$row['Other Note'];

		}else {
			$enable_other[$category->data['Category Parent Key']]=false;
		}


	}

	//print_r($other_value);

	$smarty->assign('other_value',$other_value);
	$smarty->assign('enable_other',$enable_other);




}
elseif ($view=='change_password') {

	$rnd='';
	for ($i = 0; $i < 16; $i++) {
		$rnd .= substr("./ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789",
			mt_rand(0, 63), 1);
	}

	$epwcp1=sprintf("%sinsecure_key%s",$user->id,$rnd);

	$smarty->assign('epwcp1',$epwcp1);
	$smarty->assign('rnd',$rnd);

	$js_files[]='js/aes.js';
	$js_files[]='js/sha256.js';

	$title=_('Change Password');
	$page->set_title=$title;


}
elseif ($view=='delivery_addresses') {

	$js_files[]='js/country_address_labels.js';

	$js_files[]='js/edit_address.js';

	$js_files[]='js/edit_delivery_address_common.js';
		$title=_('Delivery Addresses');
	$page->set_title=$title;
	

}elseif ($view=='billing_addresses') {

	$js_files[]='js/country_address_labels.js';

	$js_files[]='js/edit_address.js';
	$js_files[]='js/edit_billing_address_common.js';
	
		$title=_('Billing Addresses');
	$page->set_title=$title;
	

}elseif ($view=='orders') {


	$css_files[]='css/table.css';
	$base_js_files[]='js/table_common.js';
	$title=_('Orders');
	$rrp=$_SESSION['state']['customer']['orders']['nr'];
	$_order=$_SESSION['state']['customer']['orders']['order'];
	$_order_dir=$_SESSION['state']['customer']['orders']['order_dir'];
	$smarty->assign('rrp',$rrp);
	$smarty->assign('_order',$_order);
	$smarty->assign('_order_dir',$_order_dir);
	
	$title=_('Orders');
	$page->set_title=$title;
	

}elseif ($view=='order') {
	$css_files[]='css/order.css';
	if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0) {
		header('Location: profile.php?view=orders&error=wrong_order_id');
		exit;
	}

	$order=new Order($_REQUEST['id']);
$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);

	if (!$order->id) {
		header('Location: profile.php?view=orders&error=order_not_found');
		exit;
	}

	if ($order->data['Order Customer Key']!=$customer->id) {




		header('Location: profile.php?view=orders&error=forbidden');
		exit;
	}

	$css_files[]='css/table.css';
	$base_js_files[]='js/table_common.js';
	$title=_('Order').': '.$order->get('Order Public ID');
$page->set_title=$title;

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



	$smarty->assign('order_date',strftime("%a %e %b %Y", strtotime($order->data['Order Date'].' UTC')));

	$smarty->assign('order',$order);
	$smarty->assign('customer',$customer);

	$last_basket_page_key=$order_in_process->get_last_basket_page();
	if (!$last_basket_page_key) {
		$last_basket_page_key=$site->get_page_key_from_section('Front Page Store');
	}
	$smarty->assign('last_basket_page_key',$last_basket_page_key);

	$charges_deal_info=$order_in_process->get_no_product_deal_info('Charges');
	if ($charges_deal_info!='') {
		$charges_deal_info='<span style="color:red" title="'.$charges_deal_info.'">*</span> ';
	}
	$smarty->assign('charges_deal_info',$charges_deal_info);
	$smarty->assign('distinct_set_currency',($_SESSION['set_currency']!=$order->data['Order Currency']?0:1));

	$smarty->assign('total_in_store_currency',money($order->data['Order Balance Total Amount'],$order->data['Order Currency']));


}





$smarty->assign('return_to_order','');


$smarty->assign('parent','profile');
$smarty->assign('customer',$customer);


$smarty->assign('css_files',array_merge( $base_css_files,$css_files));
$smarty->assign('js_files',array_merge( $base_js_files,$js_files));
$smarty->assign('title',$title);

$smarty->display('page.tpl');

?>
