<?php
include_once 'conf/key.php';
include_once 'aes.php';
require_once 'app_files/db/dns.php';
require_once "conf/checkout.php";
require_once 'common_functions.php';
require_once 'common_store_functions.php';
require_once 'common_detect_agent.php';

//require_once 'ar_show_products.php';
require_once "class.Session.php";
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
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
mysql_query("SET time_zone='+0:00'");
require_once 'conf/conf.php';

$yui_path="external_libs/yui/2.9/build/";


$max_session_time=1000000;
$session = new Session($max_session_time,1,100);



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


$checkout_method=$site->data['Site Checkout Method'];


$secret_key=$site->data['Site Secret Key'];

$store_key=$site->data['Site Store Key'];
$store=new Store($store_key);



$store_code=$store->data['Store Code'];

setlocale(LC_MONETARY, $site->data['Site Locale']);
$authentication_type='login';

$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;



if ($logout) {
	$auth=new Auth(IKEY,SKEY);

	//$auth->unset_cookies();
	$sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
	mysql_query($sql);


	//session_regenerate_id();
	//session_destroy();
	//unset($_SESSION);



	$_SESSION = array();


	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();

		setcookie('sk', '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
		setcookie('page_key', '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
		$resxx=setcookie('user_handle', '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);

	}


	session_destroy();
	$_SESSION['logged_in']=0;
	session_regenerate_id();


	$logged_in=false;
	$St=get_sk();
}
elseif (isset($_REQUEST['p'])) {

	$dencrypted_secret_data=AESDecryptCtr(base64_decode($_REQUEST['p']),$secret_key,256);

	$auth=new Auth(IKEY,SKEY);

	$auth->authenticate_from_masterkey($dencrypted_secret_data);

	if ($auth->is_authenticated()) {
		$authentication_type='masterkey';
		$_SESSION['logged_in']=true;
		$_SESSION['store_key']=$store_key;
		$_SESSION['site_key']=$site->id;
		$_SESSION['user_key']=$auth->get_user_key();
		$_SESSION['customer_key']=$auth->get_user_parent_key();
		$_SESSION['user_log_key']=$auth->user_log_key;

		header('location: profile.php?view=change_password');
		exit;

	} else {

		$_SESSION['logged_in']=0;
		unset($_SESSION['user_key']);
		unset($_SESSION['customer_key']);
		unset($_SESSION['user_log_key']);
		$logged_in=false;
		$St=get_sk();
	}


}elseif (isset($_COOKIE['user_handle'])) {

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
	$logged_in=false;
	$St=get_sk();



}
//print_r($_SERVER);




$user_click_key=log_visit($session->id,(isset($_SESSION['user_log_key'])?$_SESSION['user_log_key']:0),$user,$site->id);

function log_visit($session_key,$user_log_key,$user,$site_key) {



	$user_click_key=0;
	// $file = $_SERVER["SCRIPT_NAME"]; //current file path gets stored in $file
	$file = $_SERVER["PHP_SELF"];
	//echo $file;

	$break = explode('/', $file);
	$cur_file = $break[count($break) - 1];
	if (preg_match('/^ar\_/',$cur_file)) {
		return;
	}

	if (preg_match('/^ar_/',$cur_file) or preg_match('/\.js/',$cur_file)) {
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


	$date=date("Y-m-d H:i:s");


	if ($_SERVER['PHP_SELF']=='/process.php') {
		$current_url=get_current_url_from_process();
	}else {
		$current_url=get_current_url();


	}


	$sql1=sprintf("INSERT INTO `User Click Dimension` (

                  `User Key` ,
                  `User Log Key`,
                  `URL` ,

                  `Page Key` ,
                  `Date` ,

                  `Previous Page` ,
                  `Session Key` ,
                  `Previous Page Key`,`Browser`,`OS`,`IP`
                  )
                  VALUES (
                  %d,%d,%s,

                  %d,%s,

                  %s, %d,%d,
                  %s,%s,%s
                  );",
		$user_key,
		$user_log_key,
		prepare_mysql($current_url),

		0,
		prepare_mysql($date),

		prepare_mysql($previous_url,false),
		$session_key,
		$prev_page_key,
		prepare_mysql(get_user_browser($_SERVER['HTTP_USER_AGENT'])),
		prepare_mysql(get_user_os($_SERVER['HTTP_USER_AGENT'])),
		prepare_mysql(ip(),false)
	);




//	 print($sql1);
	mysql_query($sql1);
	$user_click_key= mysql_insert_id();

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
	$sql=sprintf("update `User Click Dimension`  set `Page Key`=%d where `User Click Key`=%d",$page_key,$user_click_key);
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
