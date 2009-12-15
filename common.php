<?php
define('DEBUG', 1);
if(DEBUG){ error_reporting(E_ALL);}

//$path = 'classes';set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'app_files/db/dns.php'; 
require_once 'common_functions.php';
require_once "class.Session.php";
require_once "class.Auth.php";
require_once "class.User.php";






$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$default_DB_link){print "Error can not connect with database server\n";}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected){print "Error can not access the database\n";exit;}

mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';   
date_default_timezone_set(TIMEZONE) ;

//mysql_query(sprintf("SET time_zone =%s",prepare_mysql(TIMEZONE)));
mysql_query("SET time_zone='+0:00'");
require_once 'conf/conf.php';   

$session = new Session($myconf['max_session_time'],1,100);
//print_r($_SESSION);
//print '//'.session_id( );
//print '//'.$_SESSION['state']['store']['plot'];
require('external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();




$smarty->template_dir = $myconf['template_dir'];
$smarty->compile_dir = $myconf['compile_dir'];
$smarty->cache_dir = $myconf['cache_dir'];
$smarty->config_dir = $myconf['config_dir'];
$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;
if ($logout){
  $sql=sprintf("update session_history set end=NOW()  where session_id=%s  ",prepare_mysql(session_id()));
  mysql_query($sql);
  session_destroy();
  unset($_SESSION);
  include_once 'login.php';
  exit;
 }



$is_already_logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);

if(!$is_already_logged_in){
  include_once('app_files/key.php');
  $auth=new Auth(IKEY,SKEY);
  $handle = (array_key_exists('_login_', $_REQUEST)) ? $_REQUEST['_login_'] : false;
  
  
  $sk = (array_key_exists('ep', $_REQUEST)) ? $_REQUEST['ep'] : false;

  if(!$sk and array_key_exists('mk', $_REQUEST)    ){
    $auth->authenticate_from_masterkey($_REQUEST['mk']);
  }else{
    $auth->authenticate($handle,$sk);
  }
  
  if($auth->is_authenticated()){
    $_SESSION['logged_in']=true;
    $_SESSION['user_key']=$auth->get_user_key();
    $user=new User($_SESSION['user_key']);  
    $_SESSION['text_locale']=$user->data['User Preferred Locale'];
  }else{
    $target = $_SERVER['PHP_SELF'];
    if(!preg_match('/js$/',$target)) 
      include_once 'login.php';
    exit;
  }  
}else{
	$user=new User($_SESSION['user_key']);
}

include_once('set_locales.php');
require('locale.php');
$_SESSION['locale_info'] = localeconv();

$smarty->assign('lang_code',$_SESSION['text_locale_code']);
$smarty->assign('lang_country_code',strtolower($_SESSION['text_locale_country_code']));

$args="?";foreach($_GET as $key => $value){if($key!='_locale')$args.=$key.'='.$value.'&';}
$lang_menu=array();
foreach($avialable_locales as $row ){

  	$lang_menu[]=array($_SERVER['PHP_SELF'].$args.'_locale='.$row['Locale'],$row['Flag'],$_lang[$row['Language Code']]);
}

$smarty->assign('lang_menu',$lang_menu);
$smarty->assign('page_layout','doc4');


include_once('set_state.php');

$smarty->assign('user',$user->data['User Alias']);
$user->read_groups();
$user->read_rights();
$user->read_stores();
$user->read_warehouses();

//print_r($user);
//exit;
$nav_menu=array();
if($user->can_view('users'))
  	$nav_menu[] = array(_('Users'), 'users_staff.php');
else
	 $nav_menu[] = array(_('Profile'), 'user.php');
if($user->can_view('staff'))
  $nav_menu[] = array(_('Staff'), 'hr.php');
if($user->can_view('suppliers'))
  $nav_menu[] = array(_('Suppliers'), 'suppliers.php');
if($user->is('Supplier'))
  $nav_menu[] = array(_('My Products'), 'myproducts.php');

if($user->can_view('reports')){
  // if(count($user->stores)==1){
  //  $nav_menu[] = array(_('Reports'), sprintf('report_sales.php?store_key=%d&tipo=m&y=%d&m=%d',$user->stores[0],date('Y'),date('m')));
  //  }else

 $nav_menu[] = array(_('Reports'), 'reports.php');
}

if($user->can_view('orders')){

if(count($user->stores)==1){
    $nav_menu[] = array(_('Orders'), 'orders.php?store='.$user->stores[0]);
    }else
$nav_menu[] = array(_('Orders'), 'orders_server.php');

}

if($user->can_view('customers')){

  if(count($user->stores)==1){
    $nav_menu[] = array(_('Customers'), 'customers.php?store='.$user->stores[0]);
    }else
  $nav_menu[] = array(_('Customers'), 'customers_server.php');

}
if($user->can_view('warehouses'))
$nav_menu[] = array(_('Warehouse'), 'warehouse.php');


if($user->can_view('stores')){
    if(count($user->stores)==1){
    $nav_menu[] = array(_('Products'), 'store.php?id='.$user->stores[0]);
    }else
    $nav_menu[] = array(_('Products'), 'stores.php');
}

$nav_menu[] = array(_('Home'), 'index.php');

$smarty->assign('nav_menu',$nav_menu);
$smarty->assign('theme',$myconf['theme']);
$smarty->assign('my_name',$myconf['name']);


?>