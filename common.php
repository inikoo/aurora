<?
define('DEBUG', 1);
$path = 'common';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

$path_to_liveuser_dir = '/usr/share/php/'.PATH_SEPARATOR;

ini_set('include_path', $path_to_liveuser_dir.ini_get('include_path'));


require_once 'app_files/db/dns.php';         // DB connecton configuration file
require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once 'common_functions.php';
require_once "classes/DBsession.php";


 
// DEBUG STUFF --------------------------------------|
if(DEBUG){
  error_reporting(E_ALL);// For developing


 }
// __________________________________________________|

/* ------------------------------------------------------------------------------------------------|
Connectin to the database using PEAR MDB2 DB Abstraction Layer
$db is our database object.
this object can be avileable inside functions  gettin a reference to the existing database object
$db =& MDB2::singleton();
 */
//require_once 'HTML/Page2.php';


$db =& MDB2::singleton($dsn);       

if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
// To have nice arrays when we make some selects :)

$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$default_DB_link){print "Error can not connect with database server\n";exit;}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected){print "Error can not access the database\n";exit;}

$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
$db->query("SET NAMES 'utf8'");


//__________________________________________________________________________________________________|

require_once 'conf/conf.php';            // Configuration file __________________________________________|

$session = new dbsession($myconf['max_session_time'],1,100);


require('external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = $myconf['template_dir'];
$smarty->compile_dir = $myconf['compile_dir'];
$smarty->cache_dir = $myconf['cache_dir'];
$smarty->config_dir = $myconf['config_dir'];


// Authentication Stuff ----------------------------------------------------------------------------|



require_once 'LiveUser.php';        // PEAR Authentication System
$LU = LiveUser::singleton($LU_conf);
if (!$LU->init()) {
  var_dump($LU->getErrors());
  die('xx');
 }
if (!$LU) 
  die(_('An unknown error occurred'));





$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;
if ($logout){
  
  $sql=sprintf("update session_history set end=NOW()  where session_id=%s  ",prepare_mysql(session_id()));
  //print $sql;
  mysql_query($sql);

  session_destroy();
  unset($_SESSION);
  
  $LU->deleteRememberCookie();
  //  $LU->logout(true); 
  include_once 'login.php';
  exit;
 }


$handle = (array_key_exists('_login_', $_REQUEST)) ? $_REQUEST['_login_'] : false;
$sk = (array_key_exists('ep', $_REQUEST)) ? $_REQUEST['ep'] : false;




if(!$LU->isLoggedIn() || ($handle && $LU->getProperty('handle') != $handle)){
   if (!$handle){
     $LU->login(null, null, true);
   }else{
      
       include_once('aes.php');
       include_once('app_files/db/key.php');
       $sql=sprintf("select passwd from liveuser_users where handle='%s'",addslashes($handle));
       

       $result=mysql_query($sql);
       if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

	$pwd=$row['passwd'];
 	$st=AESDecryptCtr(AESDecryptCtr($sk,$pwd,256),SKEY,256);

	if(preg_match('/^skstart\|\d+\|[0-9\.]+\|.+\|/',$st)){
 	  $data=preg_split('/\|/',$st);
	  $time=$data[1];
 	  $ip=$data[2];
 	  $ikey=$data[3];
 	  if($time-date('U')>0 and ip()==$ip and IKEY==$ikey )
 	    $LU->login($handle, $pwd,true);
	}
       }
   }
 }



if(!$LU->isLoggedIn()){
  $target = $_SERVER['PHP_SELF'];
  if(preg_match('/js$/',$target)) 
    exit();
  include_once 'login.php';
  exit();
 }








// if($handle & $sk){
//   include_once('aes.php');
//   include_once('app_files/db/key.php');
//   $sql=sprintf("select passwd from liveuser_users where handle='%s'",addslashes($handle));
//   $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
//   if($row=$res->fetchRow()) {
//     $pwd=$row['passwd'];
    
//     $st=AESDecryptCtr(AESDecryptCtr($sk,$pwd,256),SKEY,256);
//     if(preg_match('/^skstart\|\d+\|[0-9\.]+\|.+\|/',$st)){
//       $data=preg_split('/\|/',$st);
//       $time=$data[1];
//       $ip=$data[2];
//       $ikey=$data[3];
//       if($time-date('U')>0 and ip()==$ip and IKEY==$ikey ){
// 	$LU->login($handle, $pwd, true);
//       }
//     }
//   }
  
 
  
//   if ($LU->isLoggedIn()){
//     $end=date('Y-m-d H:i:s',strtotime($myconf['max_session_time'].' sec'));
//     $sql=sprintf("insert into session_history (user_id,ip,start,last,end,session_id) values (%d,%s,NOW(),NOW(),%s,%s)   ",$LU->getProperty('auth_user_id'),prepare_mysql(ip()),prepare_mysql($end),prepare_mysql(session_id()) );
//     mysql_query($sql);
//   }else{
//     $sql=sprintf("insert into session_noauth (handle,date,ip) values (%s,NOW(),%s)",prepare_mysql($handle),prepare_mysql(ip()));
//     //  print "$sql";
//     mysql_query($sql);
//     $target = $_SERVER['PHP_SELF'];
//     if(preg_match('/js$/',$target))
//       exit();
//     include_once 'login.php';
//     exit();
  
//   }

//  }else{

//   if ($LU->isLoggedIn()) {
//     $end=date('Y-m-d H:i:s',strtotime($myconf['max_session_time'].' sec'));
//     $sql=sprintf("update session_history set last=NOW(),end=%s  where session_id=%s  ",prepare_mysql($end),prepare_mysql(Session_id()));

//     mysql_query($sql);
//   }else{
//     //     $sql=sprintf("insert into session_noauth (handle,ip,date,ip) values (NULL,NOW(),%s)",prepare_mysql(ip()));
//     //print $sql;
//     //mysql_query($sql);
//     print "caca";
    
//     $target = $_SERVER['PHP_SELF'];
//     if(preg_match('/js$/',$target))
//       exit();
//     include_once 'login.php';
//     exit();

//   }

//  }




 





// Locale information-------------------------------------------------|
setlocale(LC_ALL, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));



if(isset($_SESSION['loginInfo']['auth']['propertyValues']['lang'])){
  $_SESSION['lang']=$_SESSION['loginInfo']['auth']['propertyValues']['lang'];
 }
if(isset($_REQUEST['_lang']) and is_numeric($_REQUEST['_lang'])){
  $_SESSION['lang']=$_REQUEST['_lang'];
 }
if(!isset($_SESSION['lang'])){
  
  $sql="select `Language Key`  from `Language Dimension` where `Locale Code`='".$myconf['lang'].'_'.$myconf['country']."'";
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){

    $_SESSION['lang']=$row['Language Key'];
  }else{
    $_SESSION['lang']=1;

  }
 }


$other_langs=array();

$sql="select   `EAI Locale Code` ,`Language Code` ,`Country 2 Alpha Code` , `Locale Code` from `Language Country Bridge`   where `Language Key`=".$_SESSION['lang'];


$result=mysql_query($sql);
if($sql_data=mysql_fetch_array($result, MYSQL_ASSOC)   ){

  // setlocale(LC_MESSAGES, $sql_data['code'].'_'.$sql_data['country_code'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
  //setlocale(LC_TIME, $sql_data['code'].'_'.$sql_data['country_code'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
  setlocale(LC_MESSAGES, $sql_data['EAI Locale Code']);
  setlocale(LC_TIME, $sql_data['EAI Locale Code']);
  if(isset($_SESSION['loginInfo']['auth']['propertyValues']['lang']))
    $_SESSION['lang']=$_SESSION['loginInfo']['auth']['propertyValues']['lang']=$_SESSION['lang'];
  $lang_country_code=$sql_data['Country 2 Alpha Code'];
  // $default_country=$sql_data['Country Name'];
  //$default_country_id=$sql_data['country_id'];
  $lang_code=$sql_data['Language Code'];
 }else{
  $lang_country_code='gb';
  $lang_code='EN';
 }

bindtextdomain('kaktus', './locale');
bind_textdomain_codeset('kaktus', $myconf['encoding']);
textdomain('kaktus');
require('locale.php');


$_SESSION['locale_info'] = localeconv();


if(!isset($_SESSION['tables']) ){
  $_SESSION['tables']=array(	
			    'customers_list'=>array('name','yui-dt-asc','25','0','where contact_id>0 and (num_invoices+num_invoices_nd)>0' ,'cu.name',''),
			    'order_list'=>array('date_index','yui-dt-asc','25','0','where true','public_id',''),
			    'contacts_list'=>array('name','yui-dt-asc','25','0'),
			    'pindex_list'=>array('code','yui-dt-asc','25','0','where true ','p.code',''),
			    'departments_list'=>array('name','yui-dt-desc','25','0','where true','name',''),
			    
			    'families_list'=>array('name','yui-dt-desc',25,0,0,'where true','name',''),
			    'products_list'=>array('code','yui-dt-desc',25,0,0,'where true','code',''),
			    'suppliers_list'=>array('code','yui-dt-asc','25',0,'where true','code',''),
			    'product_withsupplier'=>array('code','yui-dt-asc','25','0',0,'where true','p.code',''),
			    'po_item'=>array('code','yui-dt-asc','25','0',array(0,0),'where true','p.code',''),

			    'order_withcustprod'=>array('date_index','yui-dt-asc','25','0',0,'','customer_name',''),
			    'order_withcust'=>array('date_index','yui-dt-asc','25','0',0,'','customer_name',''),

			    'stock_history'=>array('op_date','yui-dt-asc',25,0,0,'where true ','','','1,1,1,0','',''),

			    'dn_item'=>array(0),
			    
			    'users_list'=>array('handle','yui-dt-asc','25','0'),
			    'groups_list'=>array('id','yui-dt-asc','25','0'),

			    'proinvoice_list'=>array('date_index','yui-dt-asc','25','0','where tipo=1 ','max',''),
			    'dn_list'=>array('date_index','yui-dt-asc','25','0',0,'where tipo=2 ','public_id',''),
			    'po_list'=>array('date_index','yui-dt-asc','25','0',0,'where true ','id',''),

			    'staff_list'=>array('alias','yui-dt-asc','25','0','where true ','alias',''),

			    'order_withprod'=>array('date_index','yui-dt-asc','25','0',0),
			    'transaction_list'=>array('display_order','yui-dt-asc'),

			    );
 }
if(!isset($_SESSION['views'])){
  $_SESSION['views']=array(
			   'departments'=>array('detail'=>false,'view'=>'general'),
			   'edit_products_block'=>'description',
			   'assets'=>'dept',
			   'product_plot'=>'sales_week',
			   'sales_plot'=>'net_sales_month',
			   'product_blocks'=>array(0,1,1,0,1,0),
			   'supplier_blocks'=>array(1,1),
			   'po_item'=>array(0,0,1),
			   'pos_table_options'=>array(1,1,1,1),
			   'stockh_table_options'=>array(1,1,1,1,1),
			   'reports_front'=>'sales',
			   'reports_front_plot'=>array(
						       'stock'=>'plot_month_outofstock',
						       'sales'=>'net_sales_month'
						       ),
			   );
 }



if(!isset($_SESSION['state']))
  $_SESSION['state']=$default_state;



$smarty->assign('user',($LU->getProperty('auth_user_id')==1?_('Superuser'):$LU->getProperty('handle')));
$smarty->assign('lang_code',$lang_code);
$smarty->assign('lang_country_code',strtolower($lang_country_code));

$is_supplier=false;
if($_SESSION['loginInfo']['auth']['propertyValues']['tipo']==2)
  $is_supplier=true;



$nav_menu=array();
if($LU->checkRight(USER_VIEW))
  $nav_menu[] = array(_('Users'), 'users.php');
//else
//  $nav_menu[] = array(_('Profile'), 'user.php');
if($LU->checkRight(STAFF_VIEW))
  $nav_menu[] = array(_('Staff'), 'hr.php');
if($LU->checkRight(SUP_VIEW))
  $nav_menu[] = array(_('Suppliers'), 'suppliers.php');
if($is_supplier)
  $nav_menu[] = array(_('My Products'), 'myproducts.php');


$nav_menu[] = array(_('Reports'), 'reports.php');
if($LU->checkRight(ORDER_VIEW))
$nav_menu[] = array(_('Orders'), 'orders.php');
if($LU->checkRight(CUST_VIEW))
  $nav_menu[] = array(_('Customers'), 'customers.php');
$nav_menu[] = array(_('Warehouse'), 'warehouse.php');

$nav_menu[] = array(_('Products'), 'stores.php');
$nav_menu[] = array(_('Home'), 'index.php');

$smarty->assign('nav_menu',$nav_menu);
$smarty->assign('theme',$myconf['theme']);
$smarty->assign('my_name',$myconf['name']);

$args="?";
foreach($_GET as $key => $value){
  if($key!='_lang')
    $args.=$key.'='.$value.'&';
}
$lang_menu=array();
$sql="select `Language Key`,`Country 2 Alpha Code` from `Language Country Bridge` where `EAI Access`=1 and `Language Key`!=".$_SESSION['lang'];


 $result=mysql_query($sql);
while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
  
  $lang_menu[]=array($_SERVER['PHP_SELF'].$args.'_lang='.$row['Language Key'],$row['Country 2 Alpha Code'],$_lang[$row['Language Key']]);
 }
$smarty->assign('lang_menu',$lang_menu);
$smarty->assign('page_layout','doc4');

//regex expresions
$regex['thousand_sep']=str_replace('.','\.','/'.$myconf['thosusand_sep'].'/g');
$regex['number']=str_replace('.','\.','/^\d*'.$myconf['decimal_point'].'?\d*$/i');
$regex['strict_number']=str_replace('.','\.','/^(\d{1,3}'.$myconf['thosusand_sep'].')*\d{1,3}('.$myconf['decimal_point'].'\d+)?$/i');
$regex['dimension1']=str_replace('.','\.','/^\d+'.$myconf['decimal_point'].'?\d*$/i');
$regex['dimension2']=str_replace('.','\.','/^\d*'.$myconf['decimal_point'].'?\d*x\d*'.$myconf['decimal_point'].'?\d*$/i');
$regex['dimension3']=str_replace('.','\.','/^\d*'.$myconf['decimal_point'].'?\d*x\d*'.$myconf['decimal_point'].'?\dx\d*'.$myconf['decimal_point'].'?\d*$/i');

//$regex['strict_number']=str_replace('.','\.','/^\d{1,3}$/');



$regex['key_filter_number']=str_replace('.','\.','/[\d\b'.$myconf['decimal_point'].$myconf['thosusand_sep'].']/i');
$regex['key_filter_dimension']=str_replace('.','\.','/[x\d\b'.$myconf['decimal_point'].$myconf['thosusand_sep'].']/i');
?>