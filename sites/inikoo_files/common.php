<?php
include_once 'app_files/key.php';

include_once 'aes.php';
require_once 'app_files/db/dns.php';
//require_once "conf/checkout.php";
require_once 'common_functions.php';
require_once 'common_store_functions.php';
require_once 'common_detect_agent.php';

//require_once 'ar_show_products.php';
//require_once "class.Session.php";
require_once "class.Store.php";

require_once "class.Auth.php";
require_once "class.Page.php";

require_once "class.User.php";
require_once "class.Site.php";
require_once "class.Customer.php";
require_once "class.Product.php";
require_once "class.Family.php";
require_once "class.Invoice.php";
require_once "class.DeliveryNote.php";

require 'external_libs/Smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';
//$smarty->error_reporting = E_STRICT;

$user=false;



$user_log_key=0;
$found_in=array();
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




mysql_query("SET time_zone='+0:00'");
require_once 'conf/conf.php';

$yui_path="external_libs/yui/2.9/build/";


//$max_session_time=1000000;
//$session = new Session($max_session_time,1,100);
session_start();

if(isset($_SESSION['offset'])){
date_default_timezone_set($_SESSION['offset']);

}else{
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
}





//print_r($_SESSION);

$site=new Site($myconf['site_key']);


if (!$site->id) {

	exit ("Site data not found");
}

$locale=$site->data['Site Locale'];
//$locale='en_GB';
putenv('LC_ALL='.$locale);
setlocale(LC_ALL,$locale);

// Specify location of translation tables
bindtextdomain("inikoosites", "./locale");

// Choose domain
textdomain("inikoosites");
bind_textdomain_codeset("inikoosites", 'UTF-8');

$checkout_method=$site->data['Site Checkout Method'];


$secret_key=$site->data['Site Secret Key'];

$store_key=$site->data['Site Store Key'];
$store=new Store($store_key);



$store_code=$store->data['Store Code'];

setlocale(LC_MONETARY, $site->data['Site Locale']);
$authentication_type='login';

$_SESSION['text_locale_country_code']=substr($site->data['Site Locale'],3,2);
$_SESSION['text_locale_code']=substr($site->data['Site Locale'],0,2);


if (!isset($_SESSION['logged_in']) or !$_SESSION['logged_in'] ) {

	if (isset($_REQUEST['p'])) {

		header('Location: reset.php?x=x&master_key='.$_REQUEST['p']);
		exit;
	}

	if (isset($_REQUEST['masterkey'])) {

		$dencrypted_secret_data=AESDecryptCtr(base64_decode($_REQUEST['masterkey']),$secret_key,256);

		$auth=new Auth(IKEY,SKEY);
		$auth->log_page='customer';
		$auth->authenticate_from_masterkey($dencrypted_secret_data);

		if ($auth->is_authenticated()) {
			$authentication_type='masterkey';
			$_SESSION['logged_in']=true;
			$_SESSION['store_key']=$store_key;
			$_SESSION['site_key']=$site->id;
			$_SESSION['_state']='a';
			$_SESSION['user_key']=$auth->get_user_key();
			$_SESSION['customer_key']=$auth->get_user_parent_key();
			$_SESSION['user_log_key']=$auth->user_log_key;

			header('location: profile.php?view=change_password');
			exit;

		} else {

			$_SESSION['logged_in']=0;
			$_SESSION['_state']='b';
			unset($_SESSION['user_key']);
			unset($_SESSION['customer_key']);
			unset($_SESSION['user_log_key']);
			$logged_in=false;
			$St=get_sk();

			header('Location: reset.php?error='.$auth->pass['main_reason']);
			exit;


		}


	}
	elseif (isset($_COOKIE['user_handle'])) {

		//print_r($_COOKIE);


		$auth=new Auth(IKEY,SKEY);
		$auth->set_use_cookies();
		//$auth->use_cookies=true;
		$auth->authenticate(false, false, 'customer', $_COOKIE['page_key']);

		if ($auth->is_authenticated()) {
			$authentication_type='cookie';
			$_SESSION['logged_in']=true;
			$_SESSION['store_key']=$store_key;
			$_SESSION['site_key']=$site->id;
			$_SESSION['user_key']=$auth->get_user_key();
			$_SESSION['customer_key']=$auth->get_user_parent_key();
			$_SESSION['user_log_key']=$auth->user_log_key;
		} else {
			unset($_SESSION['user_key']);
			unset($_SESSION['customer_key']);
			unset($_SESSION['user_log_key']);
			$_SESSION['logged_in']=0;
			$logged_in=false;
			$St=get_sk();
		}
	}

}

$customer=new Customer(0);
$logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);

if (!isset($_SESSION['site_key']) ) {
	unset($_SESSION['user_key']);
	unset($_SESSION['customer_key']);
	unset($_SESSION['user_log_key']);
	$_SESSION['logged_in']=0;
	$logged_in=false;
	$St=get_sk();
}

if ($logged_in ) {
	if ($_SESSION['site_key']!=$site->id) {
		unset($_SESSION['user_key']);
		unset($_SESSION['customer_key']);
		unset($_SESSION['user_log_key']);
		$_SESSION['logged_in']=0;
		$_SESSION['_state']='c';
		$logged_in=false;
		$St=get_sk();
	} else {

		$user=new User($_SESSION['user_key']);
		
		
		$customer=new Customer($_SESSION['customer_key']);

		//print_r($customer);
	}

} else {
	unset($_SESSION['user_key']);
	unset($_SESSION['customer_key']);
	unset($_SESSION['user_log_key']);
	$_SESSION['logged_in']=0;
	$_SESSION['_state']='d';
	$logged_in=false;
	$St=get_sk();
}
//print_r($_SERVER);

if (isset($not_found_current_page)) {
	$current_url=$not_found_current_page;
}else {
	$current_url='';
}

$order=false;
//$order_in_process=$customer->get_order_in_process_key();
//if($order_in_process)
//$order=new Order($order_in_process);

$user_click_key=log_visit((isset($_SESSION['user_log_key'])?$_SESSION['user_log_key']:0),$user,$site->id,$current_url);




function log_visit($user_log_key,$user,$site_key,$current_url) {



	$visitor_key=0;
	if (isset($_COOKIE['v'.$site_key])) {
		//print_r($_COOKIE['v'.$site_key]);

		$visitor_key=AESDEcryptCtr(base64_decode($_COOKIE['v'.$site_key]),$site_key.VKEY, 256);
		if (!is_numeric($visitor_key))
			$visitor_key=0;

	}


	if (!$visitor_key) {

		$sql=sprintf("insert into `User Visitor Dimension` (`User Visitor Site Key`) values (%d) ",$site_key);
		mysql_query($sql);
		$visitor_key=mysql_insert_id();
		$encrypted_visitor_key=base64_encode(AESEncryptCtr($visitor_key,$site_key.VKEY, 256));

		setcookie('v'.$site_key, $encrypted_visitor_key, time()+63072000, "/");

	}


	$user_session_key=0;
	if (isset($_COOKIE['us'.$site_key])) {
		// print_r($_COOKIE['us'.$site_key]);

		$user_session_key=AESDEcryptCtr(base64_decode($_COOKIE['us'.$site_key]),$site_key.VKEY, 256);
		//print "$user_session_key";
		if (!is_numeric($user_session_key))
			$user_session_key=0;
		else {
			$encrypted_user_session_key=base64_encode(AESEncryptCtr($user_session_key,$site_key.VKEY, 256));

			setcookie('us'.$site_key, $encrypted_user_session_key, time()+1800, "/");
			$sql=sprintf("update `User Session Dimension` set `User Session Last Request Date`=NOW() where `User Session Key`=%d",$user_session_key);
			mysql_query($sql);
		}

	}

	if (!$user_session_key) {

		$sql=sprintf("insert into `User Session Dimension` (`User Session Visitor Key`,`User Session Site Key`,`User Session Last Request Date`,`User Session Start Date`) values (%d,%d,NOW(),NOW()) ",
			$visitor_key,
			$site_key);
		//print $sql;
		mysql_query($sql);
		$user_session_key=mysql_insert_id();
		$encrypted_user_session_key=base64_encode(AESEncryptCtr($user_session_key,$site_key.VKEY, 256));

		setcookie('us'.$site_key, $encrypted_user_session_key, time()+1800, "/");

	}



	$user_click_key=0;
	// $file = $_SERVER["SCRIPT_NAME"]; //current file path gets stored in $file
	$file = $_SERVER["PHP_SELF"];
	//echo $file;

	$break = explode('/', $file);
	$cur_file = $break[count($break) - 1];


	if (preg_match('/^ar_/',$cur_file) or preg_match('/\.js/',$cur_file) or preg_match('/securimage_show/',$cur_file)) {
		return;
	}
	//print_r($_SERVER);
	//print '|^http\:\/\/'.$_SERVER['SERVER_NAME'].'\/page\.php.+\&url=(.+)$|';
	$previous_url=(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');

	$prev_page_key=0;

	if (preg_match('|^http\:\/\/'.$_SERVER['SERVER_NAME'].'\/page\.php\?id=(\d+)|',$previous_url,$match)) {
		$prev_page_key=$match[1];
	}
	if (preg_match('|^http\:\/\/'.$_SERVER['SERVER_NAME'].'\/(.+)|',$previous_url,$match)) {
		$prev_page_code=$match[1];

		$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d and `Page Code`=%s ",
			$site_key,
			prepare_mysql($prev_page_code));

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$prev_page_key=$row['Page Key'];
		}


	}





	if ($user) {
		$user_key=$user->id;
	} else {
		$user_key=0;
	}


	$date=gmdate("Y-m-d H:i:s");



	// if ($_SERVER['PHP_SELF']=='/process.php') {
	//  $current_url=get_current_url_from_process();
	// }else {
	//  $current_url=get_current_url();
	// }


	$sql1=sprintf("INSERT INTO `User Request Dimension` (

                  `User Key` ,
                  `User Log Key`,
                  `URL` ,

                  `Page Key` ,
                  `Date` ,

                  `Previous Page` ,

                  `Previous Page Key`,`User Agent Key`,`OS`,`IP`,`User Visitor Key`,`User Session Key`
                  )
                  VALUES (
                  %d,%d,%s,

                  %d,%s,

                  %s,

                  %d,
                  %d,%s,%s,
                  %d,%d
                  );",
		$user_key,
		$user_log_key,
		prepare_mysql($current_url,false),

		0,
		prepare_mysql($date),

		prepare_mysql($previous_url,false),
		// $session_key,
		$prev_page_key,
		get_useragent_key($_SERVER['HTTP_USER_AGENT']),
		prepare_mysql(get_user_os($_SERVER['HTTP_USER_AGENT'])),
		prepare_mysql(ip(),false),
		$visitor_key,
		$user_session_key
	);

	mysql_query($sql1);
	$user_click_key= mysql_insert_id();

	if($user_log_key){
		$sql=sprintf("update `User Log Dimension` set `Last Visit Date`=%s where `User Log Key`=%d",
		prepare_mysql(gmdate("Y-m-d H:i:s")),
		$user_log_key
		);
	mysql_query($sql);
	print $sql;
	}


	if ($user_key) {
		$number_requests=0;
		$number_sessions=0;
		$sql=sprintf("select count(*) as num_request, count(distinct `User Session Key`) as num_sessions  from `User Request Dimension` where  `User Key`=%d",$user_key);
		$res=mysql_query($sql);
		//print "$sql\n";

		if ($row=mysql_fetch_assoc($res)) {

			$number_requests=$row['num_request'];
			$number_sessions=$row['num_sessions'];


		}


		$sql=sprintf("update `User Dimension` set `User Requests Count`=%d,`User Sessions Count`=%d, `User Last Request`=NOW() where `User Key`=%d  "     ,
			$number_requests,
			$number_sessions,


			$user_key
		);
		mysql_query($sql);
		//print $sql;

	}




	return $user_click_key;

}

function show_footer() {
	include_once 'footer.php';
	echo $footer;
}

function get_sk() {


	$Sk="skstart|".(date('U')+300000)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
	$St=AESEncryptCtr($Sk,SKEY, 256);
	return $St;
}






function get_current_url() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft1(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['PHP_SELF'];
}
function get_current_url_from_process() {
	$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
	$protocol = strleft1(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
	$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
	return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
}


function strleft1($s1, $s2) {
	return substr($s1, 0, strpos($s1, $s2));
}


function update_page_key_visit_log($page_key,$user_click_key) {
	$_SESSION['page_key']=$page_key;
	$sql=sprintf("update `User Request Dimension`  set `Page Key`=%d where `User Request Key`=%d",$page_key,$user_click_key);
	mysql_query($sql);
}

function ieversion() {
	$match=preg_match('/MSIE ([0-9]\.[0-9])/',$_SERVER['HTTP_USER_AGENT'],$reg);
	if ($match==0)
		return 200;
	else
		return floatval($reg[1]);
}




?>
