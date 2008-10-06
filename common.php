<?
define('DEBUG', 1);
$path = 'common';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);


require_once 'app_files/db/dns.php';         // DB connecton configuration file
require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once 'common_functions.php';
require_once "class.dbsession.php";





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
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  

$session = new dbsession(3600,1,100);

//__________________________________________________________________________________________________|

require_once 'myconf/conf.php';            // Configuration file __________________________________________|
require('/usr/share/php/smarty/Smarty.class.php');
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
  die('');
 }
if (!$LU) 
  die(_('An unknown error occurred'));


$handle = (array_key_exists('_login_', $_REQUEST)) ? $_REQUEST['_login_'] : null;
$passwd = (array_key_exists('_st1_', $_REQUEST)) ? $_REQUEST['_st1_'] : null;
$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;
if ($logout){
  
  session_destroy();
  unset($_SESSION);
  $LU->deleteRememberCookie();
  include_once 'login.php';
  exit;
 }

if(!$LU->isLoggedIn()){
  if($handle & $passwd){
    $LU->login($handle, $passwd, true);
    if ($LU->isLoggedIn()){
      $sql="insert into session (user_id,ip,start,last) values (".$LU->getProperty('auth_user_id').",'".ip()."',NOW(),NOW())";
      mysql_query($sql);
      $session_id=mysql_insert_id();
      $_SESSION['mysession_id']=$session_id;
    }
    
  }
  else{
    $LU->login(null, null, true);
    if ($LU->isLoggedIn()){

    }

  }

  if (!$LU->isLoggedIn()) {
  
  $target = $_SERVER['PHP_SELF'];
  if(preg_match('/js$/',$target))
    exit();
  include_once 'login.php';
  exit();
  }
 }

//print_r($_SESSION);


// elseif($LU->isLoggedIn()){
  
// }else{
//   if($handle){
//     $LU->login($handle, $passwd, true);

//   else{
//     $LU->login(null, null, true);// try to conect from cookie
 





// Locale information-------------------------------------------------|
setlocale(LC_ALL, $myconf['lang'].'_'.$myconf['country'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));



if(isset($_SESSION['loginInfo']['auth']['propertyValues']['lang'])){
  $_SESSION['lang']=$_SESSION['loginInfo']['auth']['propertyValues']['lang'];
 }
if(isset($_REQUEST['_lang']) and is_numeric($_REQUEST['_lang'])){
  $_SESSION['lang']=$_REQUEST['_lang'];
 }
if(!isset($_SESSION['lang'])){
  
  $sql="select id  from lang where code='".$myconf['lang'].'_'.$myconf['country']."'";
  $res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row=$res->fetchRow()) {
    $_SESSION['lang']=$row['id'];
  }else{
    $_SESSION['lang']=1;

  }
 }




$sql="select  country_id,country.name as country_name,lang.id as lang_id,lang.code,country.code2 as country_code  from lang left join list_country as country on (country.id=country_id) where lang.id=".$_SESSION['lang'];
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
$other_langs=array();
if($sql_data=$res->fetchRow()) {
 setlocale(LC_MESSAGES, $sql_data['code'].'_'.$sql_data['country_code'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));
 setlocale(LC_TIME, $sql_data['code'].'_'.$sql_data['country_code'].($myconf['encoding']!=''?'.'.$myconf['encoding']:''));

 if(isset($_SESSION['loginInfo']['auth']['propertyValues']['lang']))
   $_SESSION['lang']=$_SESSION['loginInfo']['auth']['propertyValues']['lang']=$_SESSION['lang'];
 $lang_country_code=$sql_data['country_code'];
 $default_country=$sql_data['country_name'];
 $default_country_id=$sql_data['country_id'];
 $lang_code=$sql_data['code'];
}

bindtextdomain('kaktus', './locale');
bind_textdomain_codeset('kaktus', $myconf['encoding']);
textdomain('kaktus');
require('locale.php');


$_SESSION['locale_info'] = localeconv();


if(!isset($_SESSION['tables'])){
  $_SESSION['tables']=array(	
			    'customers_list'=>array('name','yui-dt-asc','25','0','where true' ,'cu.name',''),
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
			    'order_list'=>array('date_index','yui-dt-asc','25','0','where true','public_id',''),
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
			   'edit_products_block'=>'description',
			   'assets'=>'tree',
			   'assets_tables'=>0,
			   'product_plot'=>'sales_week',
			   'sales_plot'=>'net_sales_month',
			   'product_blocks'=>array(0,1,1,0,1,0),
			   'supplier_blocks'=>array(1,1),
			   'po_item'=>array(0,0,1),
			   'pos_table_options'=>array(1,1,1,1),
			   'stockh_table_options'=>array(1,1,1,1,1),
			   'reports_front'=>'sales',
			   'reports_front_plot'=>array(
						       'sales'=>'net_sales_month'
						       ),
			   );
 }








$smarty->assign('user',($LU->getProperty('auth_user_id')==1?_('Superuser'):$LU->getProperty('name')));
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
  $nav_menu[] = array(_('Staff'), 'hresources.php');
if($LU->checkRight(SUP_VIEW))
  $nav_menu[] = array(_('Suppliers'), 'suppliers.php');
if($is_supplier)
  $nav_menu[] = array(_('My Products'), 'myproducts.php');


$nav_menu[] = array(_('Reports'), 'reports.php');
if($LU->checkRight(ORDER_VIEW))
$nav_menu[] = array(_('Orders'), 'orders.php');
if($LU->checkRight(CUST_VIEW))
  $nav_menu[] = array(_('Customers'), 'customers.php');
$nav_menu[] = array(_('Products'), 'assets_tree.php');
$nav_menu[] = array(_('Home'), 'index.php');

$smarty->assign('nav_menu',$nav_menu);
$smarty->assign('theme',$myconf['theme']);
$smarty->assign('my_name',$myconf['name']);

$args="?";
foreach($_GET as $key => $value){
  if($key!='_lang')
    $args.=$key.'='.$value.'&';
}
$sql="select lang.id as id ,lower(c.code2) as country,lang.code as code  from lang left join list_country as c on (c.id=country_id) where lang.id!=".$_SESSION['lang'];
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
$lang_menu=array();
while($row = $res->fetchrow()) {
  $lang_menu[]=array($_SERVER['PHP_SELF'].$args.'_lang='.$row['id'],$row['country'],$_lang[$row['id']]);
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