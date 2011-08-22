<?php

//$path = 'classes/';
//set_include_path(get_include_path() . $path);
//print get_include_path() . PATH_SEPARATOR . $path;
  include_once('conf/key.php');
            include_once('aes.php');
 
require_once 'conf/dns.php';
require_once("conf/checkout.php");
require_once 'common_functions.php';
require_once 'ar_show_products.php';

require_once "classes/class.Session.php";

require_once "classes/class.Auth.php";
require_once "classes/class.User.php";
require_once "classes/class.Site.php";
require_once "classes/class.LightCustomer.php";

require_once "classes/class.LightProduct.php";
require_once "classes/class.LightFamily.php";




$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
    print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
    print "Error can not access the database\n";
    exit;
}
mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
mysql_query("SET time_zone='+0:00'");
require_once 'conf/conf.php';

$yui_path="external_libs/yui/2.9/build/";
$pics_path='http://tunder/';

$max_session_time=36000;
$session = new Session($max_session_time,1,100);
//require('external_libs/Smarty/Smarty.class.php');
//$smarty = new Smarty();

$public_url=$myconf['public_url'];
if(!isset($_SESSION['basket'])){
$_SESSION['basket']=array('items'=>0,'total'=>0);

}

if(isset($_REQUEST['qty']) and is_numeric($_REQUEST['qty'])){
$_SESSION['basket']['items']=$_REQUEST['qty'];
}
if(isset($_REQUEST['tot']) and is_numeric($_REQUEST['tot'])){
$_SESSION['basket']['total']=$_REQUEST['tot'];
}

$site=new Site($myconf['site_key']);


if(!$site->id){

exit ("Site data not found");
}

$secret_key=$site->data['Site Secret Key'];

$store_key=$site->data['Site Store Key'];
$store=new Store($store_key);

	//$storetelephone=$store->data['Store Telephone'];
//		$address=$store->data['Store Address'];

$store_code=$store->data['Store Code'];
//$smarty->assign('store_code',$store_code);
//$smarty->assign('store_key',$store_key);

$_client_locale=$store->data['Store Locale'].'.UTF-8';
setlocale(LC_MONETARY, $_client_locale);


$traslated_labels=array();

if (file_exists($store_code.'/labels.php')) {
    require_once $store_code.'/labels.php';
} else {
    require_once 'conf/labels.php';
}



$_SESSION ['lang']='';



$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;
 
if ($logout) {
 
     $sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
   mysql_query($sql);
 
    session_regenerate_id();
    session_destroy();
    unset($_SESSION);
 
   // include_once 'login.php';
   // exit;
   
   $_SESSION['logged_in']=0;
$logged_in=false;
$St=get_sk();
   
}

$authentication_type='login';

if(isset($_REQUEST['p'])){


$dencrypted_secret_data=AESDecryptCtr(base64_decode($_REQUEST['p']),$secret_key,256);
// print "$dencrypted_secret_data\n";
 $auth=new Auth(IKEY,SKEY);

    $auth->authenticate_from_masterkey($dencrypted_secret_data);

	if ($auth->is_authenticated()) {
	$authentication_type='masterkey';
		$_SESSION['logged_in']=true;
		$_SESSION['store_key']=$store_key;
		$_SESSION['site_key']=$site->id;

		$_SESSION['user_key']=$auth->get_user_key();
		$_SESSION['customer_key']=$auth->get_user_parent_key();
		
		
		}


}
else{
	$auth=new Auth(IKEY,SKEY);
	//$auth->use_cookies=true;
	$auth->authenticate();

	if ($auth->is_authenticated()) {
	$authentication_type='cookie';
		$_SESSION['logged_in']=true;
		$_SESSION['store_key']=$store_key;
		$_SESSION['site_key']=$site->id;

		$_SESSION['user_key']=$auth->get_user_key();
		$_SESSION['customer_key']=$auth->get_user_parent_key();
	}
}


$logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);
if(!isset($_SESSION['site_key']) or !isset($_SESSION['user_key'])){
$_SESSION['logged_in']=0;
$logged_in=false;
$St=get_sk();
}

if ($logged_in ) {

    if ($_SESSION['site_key']!=$site->id) {
        $_SESSION['logged_in']=0;
        $logged_in=false;
        $St=get_sk();
    } else {

        $user=new User($_SESSION['user_key']);
        $customer=new LightCustomer($_SESSION['customer_key']);

    }

} 
else {

$_SESSION['logged_in']=0;
$logged_in=false;
$St=get_sk();


 
}

log_visit($session->id);





function show_footer(){
	include_once('footer.php');
	echo $footer;
}

function get_sk(){
      

   $Sk="skstart|".(date('U')+300)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
        $St=AESEncryptCtr($Sk,SKEY, 256);
return $St;
}



function show_product($code){
	global $logged_in, $ecommerce_url, $username, $method,$store_key;
	$product=new LightProduct($code, $store_key);

	if(!$product->match)
		return;

	
	$data=array('ecommerce_url'=>$ecommerce_url,'username'=>$username,'method'=>$method);
	
	if($logged_in){
		print $product->get_full_order_form('ecommerce', $data);

	}else{

		print $product->get_info();

	}

}



function show_products($code,$options=false){
	global $logged_in,$ecommerce_url_multi, $username, $method,$store_key;
	
	
	
	
	$conf= array('ecommerce_url_multi'=>$ecommerce_url_multi
				,'username'=>$username
				,'method'=>$method
				,'secure'=>(empty($secure) ? '' : $_SERVER["HTTPS"])
				,'_port'=>$_SERVER["SERVER_PORT"]
				,'_protocol'=>$_SERVER["SERVER_PROTOCOL"]
				,'url'=>$_SERVER['REQUEST_URI']
				,'server'=>$_SERVER['SERVER_NAME']
				);

	
	$code_list=array();
	$data=array();


	if(preg_match('/,/', $code)){
		$code_list=explode(',', $code);
		
		foreach($code_list as $code){
			$product=new LightProduct($code, $store_key);
			if($product->match){
				$data[]=$product->data;

			}
		}
		
		$price=$data[0]['Product Price'];
		foreach($data as $val){
			if($price>$val['Product Price'])
				$price=$val['Product Price'];
		}
		
		
		if($logged_in){
			echo show_products_in_family('ecommerce', $data, $conf, $options);
			return;
		}
		else{
		    $options=array();
			echo show_products_in_family_info($data, $options);
			return;
		}
	}
	else{
	}

	
	
	
	$product=new LightFamily($code, $store_key);
	if(!$product->match)
		return;
	
	
	$header=array('on'=>true);
	$s = empty($secure) ? '' : $_SERVER["HTTPS"];
	if($logged_in){
		echo $product->get_product_list_with_order_form($header, 'ecommerce', $s, $_SERVER["SERVER_PORT"], $_SERVER["SERVER_PROTOCOL"], $_SERVER['REQUEST_URI'], $_SERVER['SERVER_NAME'], $ecommerce_url_multi, $username, $method);
	}
	else{
		echo $product->get_product_list_no_price($header, $options);
		return;
	}
}

function set_parameters($data=false){

	if(!isset($data['code']))
		$code='';
	else
		$code=$data['code'];
	//print_r( $data);
	global $found_in_url, $found_in_label, $see_also, $footer_description, $header_title;
	$see_also=array();
	
	if(!isset($data['found_in'])){
		list($found_in_label, $found_in_url)=found_in($code);
	}
	else{
		list($found_in_label, $found_in_url)=explode(",", $data['found_in']);
	}
	
	if(!isset($data['see_also'])){
		$see_also=see_also($code);
	}
	else{
		$see_also_temp=explode(";", $data['see_also']);
		foreach($see_also_temp as $val){
			list($label, $url)=explode(",", $val);
			$see_also[$label]=$url;
		}
	}	
	
	
	

	
	if(isset($data['header_title'])){
		$header_title=$data['header_title'];
	}
	else
		$header_title="";
	$footer_description=$data['footer_description'];
}

function add_extra_header_content($data){
	$files=explode(",", $data);
	
	foreach($files as $file){
		$file="../../inikoo_files/".$file;
		include_once($file);
	}
}

function log_visit($session_key) {


global $user_click_key;
$user_click_key=0;
   // $file = $_SERVER["SCRIPT_NAME"]; //current file path gets stored in $file
$file = $_SERVER["PHP_SELF"];
//echo $file;
 
 $break = Explode('/', $file);
    $cur_file = $break[count($break) - 1];
if(preg_match('/^ar\_/',$cur_file)){
    return;
}

if(preg_match('/^ar_/',$cur_file) or preg_match('/\.js/',$cur_file)){
return;
}

// function to get the full url of the current page
function slfURL() 
{ $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
 $protocol = strleft1(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
}

function ecommerceURL() 
{ $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
 $protocol = strleft1(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
 if(strpos($_SERVER['REQUEST_URI'], "?")){
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.strleft1(strtolower($_SERVER['REQUEST_URI']), "?"); 
 }
 else
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];//.strleft1(strtolower($_SERVER['REQUEST_URI']), "?"); 
}

 function strleft1($s1, $s2) 
{ return substr($s1, 0, strpos($s1, $s2)); }

$cur_fullurl=slfURL();
//print "$cur_fullurl<br>";
$break = Explode('/', $cur_fullurl);
$cur_url = $break[count($break) - 1];
//print $cur_url;



//echo $file;
   // print "current file : $cur_file <br>";           //current file name gets stored in $file

    $purl = (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');        //previous page url
    $break = Explode('/', $purl);
    $prev_url = $break[count($break) - 1];   //previous page file name with value passed to it

    //$pos = strpos($prev_url, '?');

    //$prev_file = substr($prev_url,0, $pos);
   // print "previous file : $prev_file<br>";
//echo("<br>");



    if (isset($user)) {
        $user_key=$user->id;
    } else {
        $user_key=0;
    }

    $page_key=0;
    $date=date("Y-m-d H:i:s");
   
   
   if(isset($_SESSION['prev_page_key']))
    $prev_page_key=$_SESSION['prev_page_key'];
  else
  $prev_page_key=0;
  
  
        $sql1=sprintf("INSERT INTO `User Click Dimension` (

                      `User Key` ,
                      `URL` ,
                      `Page Key` ,
                      `Date` ,
                      `Previous Page` ,
                      `Session Key` ,
                      `Previous Page Key`
                      )
                      VALUES (
                      %d,%s, %d,%s, %s, %d,%d
                      );",
                      $user_key,
                      prepare_mysql($cur_url),
                      $page_key,
                      prepare_mysql($date),
                      prepare_mysql($prev_url),
                      $session_key,
                      $prev_page_key
                     );

        //print($sql1);
        mysql_query($sql1);
        $user_click_key= mysql_insert_id();

  

}
function update_page_key_visit_log($page_key){
    global $user_click_key;
    $sql=sprintf("update `User Click Dimension`  set `Page Key`=%d where `User Click Key`=%d",$page_key,$user_click_key);
    mysql_query($sql);

}

function found_in($code){
	global $store_key;
	
	$family=new LightFamily($code, $store_key);
	//print_r($family);
	if(!$family->id)
		return;
	return $family->get_found_in();
	
}

function see_also($code, $url="http://www.ancientwisdom.biz/forms/"){
	global $store_key;
	$family=new LightFamily($code, $store_key);
	if(!$family->id)
		return;
	return $family->get_see_also($code, $url);
}
/*
setcookie('user_handle', 'ghjghj', time()+60*60*24*365);
if(isset($_REQUEST['user_handle']))
	print $_REQUEST['user_handle'];
else
	print 'not set';
	*/
?>
