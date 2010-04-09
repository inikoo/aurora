<?php
include_once('common.php');
include_once('class.Store.php');
include_once('class.Customer.php');

include_once('aes.php');
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
		,'js/reset.js.php'
		,'js/dropdown.js'
);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

if(isset($_REQUEST['e'])){
  $smarty->assign('error',true);

}

$store=new Store($store_key);
$smarty->assign('store',$store);


$sql=sprintf("select `Page Store Slogan`,`Page Store Title`,`Page Code`,`Page Title`,`Page Short Title`,`Page Store Subtitle`,`Page Store Resume`,`Page Source Template` from `Page Store Dimension` PS left join `Page Dimension` P  on (P.`Page Key`=PS.`Page Key`) where `Page Code`='reset' ");

$res=mysql_query($sql);
if(!$page_data=mysql_fetch_array($res)){
  header('Location: index.php');
  exit;
}

$smarty->assign('home_header_template',"pages/$store_code/home_header.tpl");
$smarty->assign('right_menu_template',"pages/$store_code/right_menu.tpl");
$smarty->assign('left_menu_template',"pages/$store_code/left_menu.tpl");


$smarty->assign('title',$page_data['Page Title']);
$smarty->assign('header_title',$page_data['Page Store Title']);
$smarty->assign('header_subtitle',$page_data['Page Store Subtitle']);
$smarty->assign('slogan',$page_data['Page Store Slogan']);

$smarty->assign('comentary',$page_data['Page Store Resume']);

if(!isset($_REQUEST['p'])){
  header('Location: reset.php?e');
  exit;
}
$encrypted_secret_data=$_REQUEST['p'];
//print $secret_key.$store_key;
//$secret_key=2;
if($secret_data= json_decode(AESDecryptCtr(base64_decode($encrypted_secret_data),$secret_key.$store_key,256),true)){
 
  $customer_key=$secret_data['C'];
  $time=date('U')-substr($secret_data['D'],2);
  if($time>3600*24)
    $smarty->assign('expired',true);
  $customer=new Customer($customer_key);

  // print $time;
}else{
  $smarty->assign('invalid',true);
}




$smarty->assign('js_files',$js_files);
$smarty->display($page_data['Page Source Template']);




?>