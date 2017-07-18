<?php
include_once 'common.php';

include_once 'search_common.php';




if (!isset($_REQUEST['q'])) {
	$q='';
}else {
	$q=$_REQUEST['q'];
}



$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);

$result=$mem->get('ECOMSEARCH'.md5(INIKOO_ACCOUNT.SITE_KEY.$q));

if (!$result) {

	$result=process_search($q);
	$mem->set('ECOMSEARCH'.md5(INIKOO_ACCOUNT.SITE_KEY.$q), $result, 3600);
}


$result=process_search($q);


$_results=array();
$_number_results=0;
foreach ($result['results'] as $results_data) {

	$sql=sprintf("select `Page State` from `Page Store Dimension` where `Page Key`=%d",$results_data['page_key']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		if ($row['Page State']=='Online') {
			$_results[$results_data['page_key']]=$results_data;
			$_number_results++;
		}
	}
}


if ($q!='') {
	if ($user) {
		$user_key=$user->id;



	} else {
		$user_key=0;

	}


	$sql=sprintf("insert into `Page Store Search Query Dimension` values (%d,%d,%d,%s,%s,%d)",
		(isset($_SESSION['user_key'])?$_SESSION['user_key']:0),
		$site->id,
		$user_key,
		prepare_mysql(gmdate("Y-m-d H:i:s")),
		prepare_mysql($q),
		$_number_results

	);

	mysql_query($sql);

}


$smarty->assign('results',$_results);
$smarty->assign('number_results',$_number_results);
$smarty->assign('did_you_mean',$result['did_you_mean']);

if ($_number_results==0)
	$formated_number_results=_("Sorry, we didn't find any result").'.';
else
	$formated_number_results=ngettext('result found','results found',$_number_results).'.';


$smarty->assign('formated_number_results',$formated_number_results);


$smarty->assign('query',$q);

$page_key=$site->get_search_page_key();

$page=new Page($page_key);

if (!$page->id) {
	header('Location: index.php?no_page');
	exit;
}


if ($page->data['Page Site Key']!=$site->id) {
	header('Location: index.php?site_page_not_match');
	//    exit("No site/page not match");
	exit;
}


if (in_array($page->data['Page Store Section Type'],array('Family','Product')) ) {

	if ($order_in_process and $order_in_process->id) {
		if ( $order_in_process->data['Order Current Dispatch State']=='Waiting for Payment Confirmation') {
			header('Location: waiting_payment_confirmation.php');
			exit;

		}
	}
}
$template_suffix='';

if ($logged_in) {
	$page->customer=$customer;
	$page->order=$order_in_process;
}

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
$base_css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'editor/assets/skins/sam/editor.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	'css/inikoo.css'

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

	'js/common.js',
	'js/edit_common.js',

	// 'js/page.js'
);

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Header External File Bridge` where `Page Header Key`=%d",$page->data['Page Header Key']);
$res=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]=sprintf(INIKOO_ACCOUNT."_css/%07d.css",$row['external_file_key']);
	else
		$base_js_files[]=sprintf(INIKOO_ACCOUNT."_js/%07d.js",$row['external_file_key']);

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Footer External File Bridge` where `Page Footer Key`=%d",$page->data['Page Footer Key']);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]=sprintf(INIKOO_ACCOUNT."_css/%07d.css",$row['external_file_key']);
	else
		$base_js_files[]=sprintf(INIKOO_ACCOUNT."_js/%07d.js",$row['external_file_key']);

}

$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Site External File Bridge` where `Site Key`=%d",$site->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]=sprintf(INIKOO_ACCOUNT."_css/%07d.css",$row['external_file_key']);
	else
		$base_js_files[]=sprintf(INIKOO_ACCOUNT."_js/%07d.js",$row['external_file_key']);

}



$sql=sprintf("select `External File Type`,`Page Store External File Key` as external_file_key from `Page Store External File Bridge` where `Page Key`=%d",$page->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	if ($row['External File Type']=='CSS')
		$base_css_files[]=sprintf(INIKOO_ACCOUNT."_css/%07d.css",$row['external_file_key']);
	else
		$base_js_files[]=sprintf(INIKOO_ACCOUNT."_js/%07d.js",$row['external_file_key']);
}

$smarty->assign('type_content','file');

$css_files[]='css/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.css';
$smarty->assign('template_string',$page->data['Page Store Content Template Filename'].$template_suffix.'.tpl');

$js_files[]='js/'.$page->data['Page Store Content Template Filename'].$template_suffix.'.js';
$js_files[]=sprintf(INIKOO_ACCOUNT."_js/page_%05d.js",$page->id);
$css_files[]=sprintf(INIKOO_ACCOUNT."_css/page_%05d.css",$page->id);

if ($site->data['Site Search Method']=='Custome') {
	$js_files[]=sprintf(INIKOO_ACCOUNT."_js/search_%02d.js",$site->id);
	$css_files[]=sprintf(INIKOO_ACCOUNT."_css/search_%02d.css",$site->id);
}else {
	$js_files[]='js/bar_search.js';
	$css_files[]='css/bar_search.css';
}
if ($site->data['Site Checkout Method']=='Mals') {
	$js_files[]='js/basket_emals_commerce.js';
}


$css_files[]=sprintf(INIKOO_ACCOUNT."_css/menu_%02d.css",$site->id);
$js_no_async_files=array("js/jquery.min.js","js/analytics.js");

$js_no_async_files[]=sprintf(INIKOO_ACCOUNT."_js/menu_%02d.js",$site->id);
$smarty->assign('js_no_async_files',join(',',$js_no_async_files));
//print_r(array_merge( $base_css_files,$css_files));
$smarty->assign('css_files',join(',',array_merge( $base_css_files,$css_files)));
$smarty->assign('js_files',join(',',array_merge( $base_js_files,$js_files)));


include 'template_assignments.php';
$smarty->display('page.tpl');




?>
