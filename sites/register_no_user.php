<?php
include_once('common.php');
include_once('class.Store.php');


	$css_files=array(
     'http://yui.yahooapis.com/combo?2.8.1/build/autocomplete/assets/skins/sam/autocomplete.css'
		 
		 ,'css/common.css'
		 ,'css/home.css'
		 ,'css/info.css'
		 ,'css/register.css'
		 ,'css/dropdown.css'
		 );
$js_files=array(
		'http://yui.yahooapis.com/combo?2.8.1/build/utilities/utilities.js&2.8.1/build/datasource/datasource-min.js&2.8.1/build/autocomplete/autocomplete-min.js&2.8.1/build/json/json-min.js'
		,'js/md5.js'
		,'js/sha256.js'
		,'js/edit_address.js.php'
		,'js/register_no_user.js.php'
		,'js/dropdown.js'
);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['we'])){
  $smarty->assign('error',true);

}

$store=new Store($store_key);
$smarty->assign('store',$store);


$sql=sprintf("select `Page Store Slogan`,`Page Store Title`,`Page Code`,`Page Title`,`Page Short Title`,`Page Store Subtitle`,`Page Store Resume`,`Page Source Template` from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`='register' and `Page Parent Key`=%d ",$store_key);
$res=mysql_query($sql);


if(!$page_data=mysql_fetch_array($res)){
  header('Location: index.php');
  exit;
}

$smarty->assign('home_header_template',"templates/home_header.".$store->data['Store Locale'].".tpl");
$smarty->assign('right_menu_template',"templates/right_menu.".$store->data['Store Locale'].".tpl");
$smarty->assign('left_menu_template',"templates/left_menu.".$store->data['Store Locale'].".tpl");


$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);

$smarty->assign('comentary',$page_data['Page Store Resume']);
$smarty->assign('hear_about_us', array(
                                0=> _('Please select one'),
                                1 => 'Craffocus Magazine',
                                2 => 'Garden Shop Catalogue',
                                3 => 'Giftfocus Magazine')
                                );
$smarty->assign('select_hear_about_us', 0);


$smarty->assign('js_files',$js_files);
$smarty->display('register_no_user.tpl');


$page_data=$store->get_page_data();
update_page_key_visit_log($page_data['Page Key']);

$_SESSION['prev_page_key']=$page_data['Page Key'];

?>
