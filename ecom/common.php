<?php

error_reporting(E_ALL ^ E_DEPRECATED);

include_once 'utils/natural_language.php';



require_once 'external_libs/Smarty/Smarty.class.php';
$smarty = new Smarty();
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';


//$smarty->caching = 1;
$smarty->clearAllCache();
//$smarty->clear_cache('index.tpl');


session_start();

$is_cached=false;

/*

if (    (!isset($_SESSION['logged_in']) or !$_SESSION['logged_in']) and isset($page_key)   and !isset($_REQUEST['p']) and !isset($_REQUEST['masterkey']) and !isset($_COOKIE['user_handle']) ) {


	$smarty->setCaching(Smarty::CACHING_LIFETIME_CURRENT);
	$smarty->setCacheLifetime(3600);
	$is_cached=$smarty->isCached('page.tpl',$page_key);


}else {
	$is_cached=false;
}
*/



if (!$is_cached) {

	include_once 'conf/key.php';
	include_once 'aes.php';
	require_once 'conf/dns.php';

	require_once 'common_functions.php';
	require_once 'common_store_functions.php';
	require_once 'common_detect_agent.php';

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

	include "geoipcity.inc";
	include "geoipregionvars.php";

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
	mysql_set_charset('utf8');
	mysql_query("SET time_zone='+0:00'");


$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);



	require_once 'conf/conf.php';

	$yui_path="js/y/";
	$tmp_images_dir='app_files/pics/';


	require_once "conf/state.php";
	if (isset($_SESSION['offset']) and $_SESSION['offset']!='') {
		date_default_timezone_set($_SESSION['offset']);


		if (!defined('TIMEZONE')) define('TIMEZONE', $_SESSION['offset']);


	}else {
		require_once 'conf/timezone.php';
		date_default_timezone_set(TIMEZONE) ;
	}

	$inikoo_account=new Account();
	$site=new Site(SITE_KEY);
	$store_key=$site->data['Site Store Key'];
	$store=new Store($store_key);


	if (!$site->id) {
		exit ("Site data not found");
	}




	$valid_currencies=array(
		'GBP'=>array(
			'name'=>'Pound sterling',
			'native_name'=>'Pound sterling',
			'symbol'=>'£',
		),
		'USD'=>array(
			'name'=>'US Dollar',
			'native_name'=>'US Dollar',
			'symbol'=>'£',
		),
		'EUR'=>array(
			'name'=>'Euro',
			'native_name'=>'Euro',
			'symbol'=>'€',
		),
		'DKK'=>array(
			'name'=>'Danish krone',
			'native_name'=>'Dansk krone',
			'symbol'=>'kr.',
		),
		'NOK'=>array(
			'name'=>'Norwegian krone',
			'native_name'=>'Norsk krone',
			'symbol'=>'kr',
		),
		'PLN'=>array(
			'name'=>'Polish złoty',
			'native_name'=>'Polski złoty',
			'symbol'=>'zł',
		),
		'SEK'=>array(
			'name'=>'Swedish krona',
			'native_name'=>'Svensk krona',
			'symbol'=>'kr',
		),
		'CHF'=>array(
			'name'=>'Swiss franc',
			'native_name'=>'Swiss franc',//'Schweizer Franken/Franc suisse/Franco svizzero',
			'symbol'=>'CHF',
		),

	);

	if (isset($_REQUEST['2alpha'])) {
		$_SESSION['ip_country_2alpha_code']=$_REQUEST['2alpha'];

	}
	else {

		if (!isset($_SESSION['ip_country_2alpha_code'])   ) {

			$ip_country_2alpha_code=$store->data['Store Home Country Code 2 Alpha'];
			$geolocation_data = geoip_open("GeoIP/GeoLiteCity.dat",GEOIP_STANDARD);



			$geolocation_record = geoip_record_by_addr($geolocation_data,ip());

			if ($geolocation_record) {

				$ip_country_2alpha_code= $geolocation_record->country_code;
			}

			$_SESSION['ip_country_2alpha_code']=$ip_country_2alpha_code;
		}
	}



	if (!isset($_SESSION['user_currency']) or !array_key_exists($_SESSION['user_currency'],$valid_currencies)  ) {

		$ip_currency='USD';
		$sql=sprintf("select `Country Currency Code` from kbase.`Country Dimension` where `Country 2 Alpha Code`=%s  ",prepare_mysql($_SESSION['ip_country_2alpha_code']));



		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$ip_currency=$row['Country Currency Code'];
		}

		if (array_key_exists($ip_currency,$valid_currencies)) {
			$user_currency=$ip_currency;
		}else {
			$user_currency='USD';
		}


		$_SESSION['user_currency']=$user_currency;
		$_SESSION['ip_currency']=$ip_currency;

	}


	if (!isset($_SESSION['set_currency']) or !array_key_exists($_SESSION['set_currency'],$valid_currencies)  ) {

		$set_currency=$store->data['Store Currency Code'];
		$_SESSION['set_currency']=$set_currency;
		$_SESSION['set_currency_exchange']=1;





	}else {

		if ($_SESSION['set_currency']!=$store->data['Store Currency Code']) {
			$set_currency_exchange=currency_conversion($store->data['Store Currency Code'],$_SESSION['set_currency']);
		}
		else {
			$set_currency_exchange=1;
		}
		$_SESSION['set_currency_exchange']=$set_currency_exchange;

	}







	$request='';
	$return_url=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$checkout_order_button_url='';
	$checkout_order_list_url='';

	if ($site->data['Site Checkout Method']=='Mals') {

		$encoded_return_url=urlencode($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$request=$site->get_checkout_data('url').'/cf/add.cfm?userid='.$site->get_checkout_data('id').'&qty=0&&price=&product=&return='.$encoded_return_url.'&nocart&sd='.session_id();
		$checkout_order_button_url=$site->get_checkout_data('url').'/cf/add.cfm?userid='.$site->get_checkout_data('id');
		$checkout_order_list_url=$site->get_checkout_data('url').'/cf/addmulti.cfm?userid='.$site->get_checkout_data('id');
		$tmp=preg_split('/\\&/',$_SERVER['QUERY_STRING']);
		$query_string=array();
		foreach ($tmp as $_value) {
			$tmp2=preg_split('/=/',$_value);
			if (count($tmp2)==2) {
				if (array_key_exists($tmp2[0],$query_string))continue;
				$query_string[$tmp2[0]]=$tmp2[1];
			}
		}
		if (isset($query_string['sd']) and isset($query_string['tot'])  and isset($query_string['qty'])  and   $query_string['sd']!='ignore' ) {
			print sprintf("<head><meta http-Equiv='Cache-Control' Content='no-cache'><meta http-Equiv='Pragma' Content='no-cache'><meta http-Equiv='Expires' Content='0'></head><script>parent.update_basket('%.2f','%d','%s')</script>%.2f ,%d,%s",
				$query_string['tot'],$query_string['qty'], $query_string['sd'],
				$query_string['tot'],$query_string['qty'], $query_string['sd']
			);
			exit;
		}
	}

	$smarty->assign('request',$request);
	$smarty->assign('selfurl',$return_url);
	$smarty->assign('checkout_order_button_url',$checkout_order_button_url);
	$smarty->assign('checkout_order_list_url',$checkout_order_list_url);



	if (!isset($_SESSION['site_locale'])) {
		$_SESSION['site_locale']=$site->data['Site Locale'];
		$site_locale=$site->data['Site Locale'];
	}




	if (isset($_REQUEST['lang']) and  in_array($_REQUEST['lang'],array('de_DE','fr_FR','it_IT','pl_PL'))) {
		$site_locale=$_REQUEST['lang'];
		$_SESSION['site_locale']=$site_locale;
	}elseif (isset($_REQUEST['lang']) and  $_REQUEST['lang']=='site') {
		$site_locale=$site->data['Site Locale'];
		$_SESSION['site_locale']=$site_locale;
	}else {
		$site_locale=$_SESSION['site_locale'];
	}

	//$site_locale='de_DE';
	//$_SESSION['site_locale']='de_DE';




	$smarty->assign('site_locale',$site_locale);


	$language=substr($site_locale,0,2);
	$smarty->assign('language',$language);

	$locale=$site_locale.'.UTF-8';

	setlocale(LC_TIME, $locale);
	setlocale(LC_MESSAGES, $locale);

	bindtextdomain("inikoosites", "./locale");
	textdomain("inikoosites");
	bind_textdomain_codeset("inikoosites", 'UTF-8');

	$checkout_method=$site->data['Site Checkout Method'];
	$secret_key=$site->data['Site Secret Key'];

	$store_code=$store->data['Store Code'];

	setlocale(LC_MONETARY, $site_locale.'.utf-8');
	$authentication_type='login';

	$_SESSION['text_locale_country_code']=substr($site_locale,3,2);
	$_SESSION['text_locale_code']=substr($site_locale,0,2);


	if (!isset($_SESSION['logged_in']) or !$_SESSION['logged_in'] ) {

		if (isset($_REQUEST['p'])) {

			header('Location: reset.php?x=x&master_key='.$_REQUEST['p']);
			exit;
		}



		if (isset($_REQUEST['masterkey'])) {

			$dencrypted_secret_data=AESDecryptCtr(base64_decode($_REQUEST['masterkey']),$secret_key,256);

			$auth=new Auth(IKEY,SKEY);
			$auth->site_key=$site->id;
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
				$sql=sprintf("update `User Log Dimension` set `Remember Cookie`='Yes'  where `User Log Key`=%d",
					$auth->user_log_key
				);
				mysql_query($sql);
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

	}
	else {
		unset($_SESSION['user_key']);
		unset($_SESSION['customer_key']);
		unset($_SESSION['user_log_key']);
		$_SESSION['logged_in']=0;
		$_SESSION['_state']='d';
		$logged_in=false;
		$St=get_sk();
	}


	if (isset($not_found_current_page)) {
		$current_url=$not_found_current_page;
	}else {
		$current_url='';
	}


	if ($logged_in and ($customer->data['Customer Store Key']!=$site->data['Site Store Key'])) {
		header('Location:  logout.php');
		exit;
	}

	$order_in_process=false;
	$order_in_process_key=$customer->get_order_in_process_key();
	$order_in_process=new Order ($order_in_process_key);
	$order_in_process->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);



}

function get_sk() {
	$Sk="skstart|".(date('U')+300000)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
	$St=AESEncryptCtr($Sk,SKEY, 256);
	return $St;
}
?>
