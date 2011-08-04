<?php

//$path = 'classes/';
//set_include_path(get_include_path() . $path);
//print get_include_path() . PATH_SEPARATOR . $path;

 
require_once 'app_files/db/dns.php';
require("conf/checkout.php");
require_once 'common_functions.php';


require_once "classes/class.Session.php";

require_once "classes/class.Auth.php";
require_once "classes/class.User.php";
require_once "classes/class.Site.php";

require_once "classes/class.LightProduct.php";
require_once "classes/class.LightFamily.php";


$secret_key='FDK/S5GRkZFXi47zvs4pTezyfEr5nWFthsFbG6j1CzCPYPX5';

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
$_SESSION['basket']=array('qty'=>0,'sub'=>0);

}else{



}

$site=new Site($myconf['site_key']);
if(!$site->id){

print_r($site);
exit ("Site data not found (".$myconf['site_key'].')<br/> click <a href="../mantenence/scripts/create_sites.php">here to create one</a> ');
}
$page_data=$site->get_data_for_smarty();



$store_key=$site->data['Site Store Key'];
$store=new Store($store_key);
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
   //     $smarty->assign('user',$user);
     //   if (isset($_SESSION['order_data']))
       //     $smarty->assign('order',$_SESSION['order_data']);



    }

} else {

$_SESSION['logged_in']=0;
$logged_in=false;
$St=get_sk();


 
}

log_visit($session->id);



//$smarty->assign('logged_in',$logged_in);



//$smarty->assign('head_template',"templates/head.".$store->data['Store Locale'].".tpl");
//$smarty->assign('footer_template',"templates/footer.".$store->data['Store Locale'].".tpl");
//$smarty->assign('main_menu_template',"templates/main_menu.tpl");

//$smarty->assign('email',$store->data['Store Email']);
//$smarty->assign('tel',$store->data['Store Telephone']);
//$smarty->assign('fax',$store->data['Store Fax']);
//$smarty->assign('store_slogan',$store->data['Store Slogan']);

function get_sk(){
        include_once('app_files/key.php');
                include_once('aes.php');

   $Sk="skstart|".(date('U')+300)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
        $St=AESEncryptCtr($Sk,SKEY, 256);
return $St;
}


function show_product($code){
	global $logged_in;
	$product=new LightProduct($code);

	
	if($logged_in){
		print $product->get_info_button();
	}else{
		print $product->get_full_order_form('ecommerce');
		//print $product->get_order_list_form();
	}

}



?>
