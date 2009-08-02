<?php
define('DEBUG', 1);
$path = 'classes';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);




require_once 'app_files/db/dns.php'; 
require_once 'common_functions.php';
require_once "class.Session.php";
require_once "class.Auth.php";
require_once "class.User.php";

 
// DEBUG STUFF --------------------------------------|
if(DEBUG){
  error_reporting(E_ALL);// For developing


 }


$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$default_DB_link){print "Error can not connect with database server\n";exit;}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected){print "Error can not access the database\n";exit;}

mysql_query("SET NAMES 'utf8'");
require_once 'conf/timezone.php';   
date_default_timezone_set(TIMEZONE) ;
mysql_query(sprintf("SET time_zone =%s",prepare_mysql(TIMEZONE)));

require_once 'conf/conf.php';   

$session = new dbsession($myconf['max_session_time'],1,100);


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
  
  $auth->authenticate($handle,$sk);
  
  if($auth->is_authenticated()){
    $_SESSION['logged_in']=true;
    $_SESSION['user_key']=$auth->get_user_key();
  }else{
    print_r($auth->pass);

    $target = $_SERVER['PHP_SELF'];
    if(!preg_match('/js$/',$target)) 
      include_once 'login.php';
    exit;
  }  
  
 }
$_SESSION['user_key']=1;
$user=new User($_SESSION['user_key']);

include_once('set_locales.php');
include_once('set_state.php');



$smarty->assign('user',$user->data['User Alias']);
$user->read_rights();

 $nav_menu=array();
if($user->can_view('users'))
  $nav_menu[] = array(_('Users'), 'users.php');
else
 $nav_menu[] = array(_('Profile'), 'user.php');
if($user->can_view('staff'))
  $nav_menu[] = array(_('Staff'), 'hr.php');
if($user->can_view('suppliers'))
  $nav_menu[] = array(_('Suppliers'), 'suppliers.php');
if($user->is('Supplier'))
  $nav_menu[] = array(_('My Products'), 'myproducts.php');


$nav_menu[] = array(_('Reports'), 'reports.php');
if($user->can_view('orders'))
$nav_menu[] = array(_('Orders'), 'orders.php');
if($user->can_view('customers'))
  $nav_menu[] = array(_('Customers'), 'customers.php');
if($user->can_view('warehouse'))
$nav_menu[] = array(_('Warehouse'), 'warehouse.php');
if($user->can_view('stores'))
  $nav_menu[] = array(_('Products'), 'stores.php');
$nav_menu[] = array(_('Home'), 'index.php');

$smarty->assign('nav_menu',$nav_menu);
$smarty->assign('theme',$myconf['theme']);
$smarty->assign('my_name',$myconf['name']);
?>