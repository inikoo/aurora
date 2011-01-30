<?php
define('DEBUG', 0);
if(DEBUG){ error_reporting(E_ALL);}

//$path = 'classes';set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'app_files/db/dns.php';

require_once 'common_functions.php';
require_once "class.Session.php";
require_once "class.Auth.php";
require_once "class.User.php";
$external_DB_link=false;

if(isset($connect_to_external) and isset($external_dns_user)){
$external_DB_link=mysql_connect($external_dns_host,$external_dns_user,$external_dns_pwd );

if(!$external_DB_link){print "Error can not connect with external database server\n";}
$external_db_selected=mysql_select_db($external_dns_db, $external_DB_link);
if (!$external_db_selected){print "Error can not access the external database\n";}
//print $external_DB_link;
}


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

/*  ?><script type = "text/javascript">alert("You are about to be signed out due to Inactivity");</script><?php   */
$sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
   mysql_query($sql);

  session_regenerate_id();
  session_destroy();
  unset($_SESSION);

  include_once 'login.php';
  exit;
 }

$is_already_logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);


if($is_already_logged_in){

if($_SESSION['logged_in_page']!=0){


    $sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
   mysql_query($sql);

  session_regenerate_id();
  session_destroy();
  unset($_SESSION);

  include_once 'login.php';
  exit;
    
    }
$user=new User($_SESSION['user_key']);

}else{
  
//   echo "<script>alert(\"Session Expired! You are signed out due to Inactivity!. Please login again!\")</script>"; 

  include_once('app_files/key.php');
  $auth=new Auth(IKEY,SKEY);
  $handle = (array_key_exists('_login_', $_REQUEST)) ? $_REQUEST['_login_'] : false;
  $sk = (array_key_exists('ep', $_REQUEST)) ? $_REQUEST['ep'] : false;

    
    
  if(!$sk and array_key_exists('mk', $_REQUEST)    ){
    $auth->authenticate_from_masterkey($_REQUEST['mk']);
  }elseif($handle){
    $auth->authenticate($handle,$sk,'staff',0);
  }
  
  if($auth->is_authenticated()){
    $_SESSION['logged_in']=true;
    $_SESSION['logged_in_page']=0;
    $_SESSION['user_key']=$auth->get_user_key();
    $user=new User($_SESSION['user_key']);  
    $_SESSION['text_locale']=$user->data['User Preferred Locale'];
  }else{
    $target = $_SERVER['PHP_SELF'];
    if(!preg_match('/js$/',$target)) 
      include_once 'login.php';
    exit;
  }  
}

$_client_locale='en_GB.UTF-8';
include_once('set_locales.php');
require('locale.php');

//print_r(localeconv());
$_SESSION['locale_info'] = localeconv();
if($_SESSION['locale_info']['currency_symbol']=='EU')
$_SESSION['locale_info']['currency_symbol']='Û';

$smarty->assign('lang_code',$_SESSION['text_locale_code']);
$smarty->assign('lang_country_code',strtolower($_SESSION['text_locale_country_code']));

$args="?";foreach($_GET as $key => $value){if($key!='_locale')$args.=$key.'='.$value.'&';}
$lang_menu=array();
foreach($avialable_locales as $row ){

  	$lang_menu[]=array($_SERVER['PHP_SELF'].$args.'_locale='.$row['Locale'],$row['Flag'],$_lang[$row['Language Code']]);
}

$smarty->assign('lang_menu',$lang_menu);
$smarty->assign('page_layout','doc4');
$smarty->assign('timezone',date("e P"));

include_once('set_state.php');

$smarty->assign('user',$user->data['User Alias']);
$user->read_groups();
$user->read_rights();
$user->read_stores();
$user->read_warehouses();
if($user->data['User Type']=='Supplier'){
$user->read_suppliers();

}


$sql=sprintf("select `Corporation Currency`,`Currency Symbol` from  `Corporation Dimension` left join kbase.`Currency Dimension` CD on (CD.`Currency Code`=`Corporation Currency`) ");
$res=mysql_query($sql);

if($row=mysql_fetch_array($res)){
$corporate_currency=$row['Corporation Currency'];
$corporate_symbol=$row['Currency Symbol'];
}

//print_r($user);
//exit;
$nav_menu=array();
if($user->can_view('users'))
  	$nav_menu[] = array(_('Users'), 'users.php','users');
else
	 $nav_menu[] = array(_('Profile'), 'user.php','users');
	 
	 
if($user->can_view('staff'))
  $nav_menu[] = array(_('Staff'), 'hr.php','staff');

if($user->can_view('reports')){
 $nav_menu[] = array(_('Reports'), 'reports.php','reports');
}
if($user->can_view('suppliers'))
  $nav_menu[] = array(_('Suppliers'), 'suppliers.php','suppliers');
if($user->can_view('warehouses')){
if($user->warehouses==1)
$nav_menu[] = array(_('Warehouse'), 'warehouse.php','warehouses');

else
$nav_menu[] = array(_('Warehouses'), 'warehouses.php','warehouses');
}

  $nav_menu[] = array(_('Marketing'), 'marketing.php','marketing');
if($user->can_view('stores')){
    if(count($user->stores)==1){
    $nav_menu[] = array(_('Products'), 'store.php?id='.$user->stores[0],'products');
    }else
    $nav_menu[] = array(_('Products'), 'stores.php','products');
}

if($user->can_view('orders')){

if(count($user->stores)==1){
    $nav_menu[] = array(_('Orders'), 'orders.php?store='.$user->stores[0],'orders');
    }else
$nav_menu[] = array(_('Orders'), 'orders_server.php','orders');

}

if($user->can_view('customers')){

  if(count($user->stores)==1){
    $nav_menu[] = array(_('Customers'), 'customers.php?store='.$user->stores[0],'customers');
    }else
  $nav_menu[] = array(_('Customers'), 'customers_server.php','customers');

}




if($user->data['User Type']=='Supplier'){


$nav_menu[] = array(_('Orders'), 'suppliers.php?orders'  ,'orders');
$nav_menu[] = array(_('Products'), 'suppliers.php?products'  ,'products');
$nav_menu[] = array(_('Dashboard'), 'suppliers_index.php','home');
}


else
  $nav_menu[] = array(_('Dashboard'), 'index.php','home');

$smarty->assign('nav_menu',$nav_menu);
$smarty->assign('theme',$myconf['theme']);
$smarty->assign('my_name',$myconf['name']);

$export_data=array(
		   'xls'=>array('label'=>_('Export as xls'),'title'=>'Excel 2005 (xls)')
		   ,'xlsx'=>array('label'=>_('Export as xlsx'),'title'=>'Excel 2007 (xlsx)')
		   ,'csv'=>array('label'=>_('Export as csv'),'title'=>_('Comma separated values (vsv)'))
		   ,'pdf'=>array('label'=>_('Export as pdf'),'title'=>'PDF')
		   );


?>
