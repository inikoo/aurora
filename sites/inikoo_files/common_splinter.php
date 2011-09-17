<?php
include_once('conf/key.php');
include_once('aes.php');

require_once 'conf/dns.php';
require_once("conf/checkout.php");
require_once 'common_functions.php';
require_once 'common_store_functions.php';


require_once 'ar_show_products.php';

require_once "class.Session.php";
require_once "class.Store.php";

require_once "class.Auth.php";
require_once "class.User.php";
require_once "class.Site.php";
require_once "class.LightCustomer.php";

require_once "class.LightProduct.php";
require_once "class.LightFamily.php";


$user_log_key=0;

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

$max_session_time=1000000;
$session = new Session($max_session_time,1,100);
//require('external_libs/Smarty/Smarty.class.php');
//$smarty = new Smarty();

//print_r($_COOKIE);
//exit;



$public_url=$myconf['public_url'];
if (!isset($_SESSION['basket'])) {
    if (!isset($_COOKIE['qty']))
        $_SESSION['basket']=array('items'=>0,'total'=>0);
    else
        $_SESSION['basket']=array('items'=>$_COOKIE['qty'],'total'=>$_COOKIE['tot']);

}

if (isset($_REQUEST['qty']) and is_numeric($_REQUEST['qty'])) {
    $_SESSION['basket']['items']=$_REQUEST['qty'];
    setcookie('qty', $_SESSION['basket']['items'], time()+60*60*2, "/");
}
if (isset($_REQUEST['tot']) and is_numeric($_REQUEST['tot'])) {
    $_SESSION['basket']['total']=$_REQUEST['tot'];
    setcookie('tot', $_SESSION['basket']['total'], time()+60*60*2, "/");
}

$site=new Site($myconf['site_key']);


if (!$site->id) {

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

$authentication_type='login';

$logout = (array_key_exists('logout', $_REQUEST)) ? $_REQUEST['logout'] : false;


//print "<br> xxx1";
//print_r($_SESSION);


if ($logout) {
    $auth=new Auth(IKEY,SKEY);
//$auth->unset_cookies($_COOKIE['user_handle'],$_COOKIE['sk'],$_COOKIE['page'],$_COOKIE['page_key']);
    $auth->unset_cookies();
    $sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
    mysql_query($sql);

    session_regenerate_id();
    session_destroy();
    unset($_SESSION);

    // include_once 'login.php';
    // exit;

    $_SESSION['logged_in']=0;
    
    
    $sql=sprintf("select ``");
    
    
    $logged_in=false;
    $St=get_sk();
}elseif(isset($_REQUEST['p'])) {


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


    } else {

        $_SESSION['logged_in']=0;
        $logged_in=false;
        $St=get_sk();
    }


}elseif(isset($_COOKIE['user_handle'])) {
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
        //echo 'jj';
    } else {

        $_SESSION['logged_in']=0;
        $logged_in=false;
        $St=get_sk();
    }
}

//print "<br> xxx2";
//print_r($_SESSION);

$logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);
if (!isset($_SESSION['site_key']) or !isset($_SESSION['user_key'])) {
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

} else {

    $_SESSION['logged_in']=0;
    $logged_in=false;
    $St=get_sk();



}

log_visit($session->id,$user_log_key);





function show_footer() {
    include_once('footer.php');
    echo $footer;
}

function get_sk() {


    $Sk="skstart|".(date('U')+300000)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
    $St=AESEncryptCtr($Sk,SKEY, 256);
    return $St;
}



function show_product($code) {
    global $logged_in, $ecommerce_url, $username, $method,$store_key;
    $product=new LightProduct($code, $store_key);

    if (!$product->match)
        return;


    $data=array('ecommerce_url'=>$ecommerce_url,'username'=>$username,'method'=>$method);

    if ($logged_in) {
        print $product->get_full_order_form('ecommerce', $data);

    } else {

        print $product->get_info();

    }

}



function show_products($code,$options=false) {
    global $logged_in,$ecommerce_url_multi, $username, $method,$store_key;




    $conf= array('ecommerce_url_multi'=>$ecommerce_url_multi,
                                       'username'=>$username,
                                                   'method'=>$method,
                                                             'secure'=>(empty($secure) ? '' : $_SERVER["HTTPS"]),
                                                                       '_port'=>$_SERVER["SERVER_PORT"],
                                                                                '_protocol'=>$_SERVER["SERVER_PROTOCOL"],
                                                                                             'url'=>$_SERVER['REQUEST_URI'],
                                                                                                    'server'=>$_SERVER['SERVER_NAME']
                );


    $code_list=array();
    $data=array();

    $header=array('on'=>true);
    $s = empty($secure) ? '' : $_SERVER["HTTPS"];
    if (preg_match('/,/', $code)) {
        $code_list=explode(',', $code);

        foreach($code_list as $code) {
            $product=new LightProduct($code, $store_key);
            if ($product->match) {
                $data[]=$product->data;

            }
        }

        $code=$code_list[0];

        $sql=sprintf("select `Product Family Key` from `Product Dimension` where `Product Code`='%s'", $code);
        //print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result))
            $family_key=$row['Product Family Key'];

        $sql=sprintf("select `Product Family Code` from `Product Family Dimension` where `Product Family Key`=%d", $family_key);
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result))
            $family_code=$row['Product Family Code'];

        /*
        $price=$data[0]['Product Price'];
        foreach($data as $val) {
            if ($price>$val['Product Price'])
                $price=$val['Product Price'];
        }
        */
        $product=new LightFamily($family_code, $store_key);
        if ($logged_in) {
            //echo show_products_in_family('ecommerce', $data, $conf, $options);
            echo $product->get_product_in_family_with_order_form($data, $header, 'ecommerce', $s, $_SERVER["SERVER_PORT"], $_SERVER["SERVER_PROTOCOL"], $_SERVER['REQUEST_URI'], $_SERVER['SERVER_NAME'], $ecommerce_url_multi, $username, $method, $options);
            return;
        } else {
            $options=array();
            //echo show_products_in_family_info($data, $options);
            echo $product->get_product_in_family_no_price($data);
            return;
        }
    } else {
    }




    $product=new LightFamily($code, $store_key);
    if (!$product->match)
        return;





    if ($logged_in) {
        echo $product->get_product_list_with_order_form($header, 'ecommerce', $s, $_SERVER["SERVER_PORT"], $_SERVER["SERVER_PROTOCOL"], $_SERVER['REQUEST_URI'], $_SERVER['SERVER_NAME'], $ecommerce_url_multi, $username, $method, $options);
    } else {
        echo $product->get_product_list_no_price($header, $options);
        return;
    }
}

function set_parameters($data=false) {

    global $found_in_url, $found_in_label, $see_also, $footer_description, $header_title,$site, $width, $path, $header_image;
	
	if(isset($data['header_image']))
		$header_image=$data['header_image'];

	//print $header_image;
	
	$width=875;
	  if(isset($data['width']))
		 $width=$data['width'];
	   if(isset($data['w']))
		 $width=$data['w'];
	   if(isset($data['width']))
		 $width=$data['width'];
	


	if (!isset($data['type']))
        $path="../../";
    else
        $path="../";

    if (!isset($data['family']))
        $family_code='';
    else
        $family_code=$data['family'];



    if (isset($data['page_code']))

        $page_code=$data['page_code'];
    else
        $page_code=$family_code;

    $page=new Page('site_code',$site->id,$page_code);



    $see_also=array();

    if (!isset($data['found_in'])) {
        list($found_in_label, $found_in_url)=$page->found_in();
    } else {
        list($found_in_label, $found_in_url)=explode(",", $data['found_in']);
    }

    if (!isset($data['see_also'])) {
        $see_also=see_also($page_code);
    } else {
        $see_also_temp=explode(";", $data['see_also']);
        foreach($see_also_temp as $val) {
            list($label, $url)=explode(",", $val);
            $see_also[_trim($label)]=_trim($url);
        }
    }





    if (isset($data['header_title'])) {
        $header_title=$data['header_title'];
    } else {
        $header_title=$page->data['Page Store Title'];
    }


    if (isset($data['footer_description']))
        $footer_description=$data['footer_description'];
}

function add_extra_header_content($data, $type="child") {
	global $path;
	//print $path;
    $files=explode(",", $data);
	/*
	if($type=="parent")
		$path="../inikoo_files/";
	else
		$path="../../inikoo_files/";
		*/
	//$path.="inikoo_files/";
    foreach($files as $file) {
        $file=$path."inikoo_files/".$file;
        include_once($file);
    }
}

 function slfURL() {
        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = strleft1(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['PHP_SELF'];
    }

    function ecommerceURL() {

        $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
        $protocol = strleft1(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        if (strpos($_SERVER['PHP_SELF'], "?")) {
            return $protocol."://".$_SERVER['SERVER_NAME'].$port.strleft1(strtolower($_SERVER['PHP_SELF']), "?");
        } else
            return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['PHP_SELF'];//.strleft1(strtolower($_SERVER['REQUEST_URI']), "?");
    }

    function strleft1($s1, $s2) {
        return substr($s1, 0, strpos($s1, $s2));
    }

function log_visit($session_key,$user_log_key) {


    global $user_click_key;
    $user_click_key=0;
    // $file = $_SERVER["SCRIPT_NAME"]; //current file path gets stored in $file
    $file = $_SERVER["PHP_SELF"];
//echo $file;

    $break = Explode('/', $file);
    $cur_file = $break[count($break) - 1];
    if (preg_match('/^ar\_/',$cur_file)) {
        return;
    }

    if (preg_match('/^ar_/',$cur_file) or preg_match('/\.js/',$cur_file)) {
        return;
    }


   

    $cur_fullurl=slfURL();
//print "$cur_fullurl<br>";
    $break = Explode('/', $cur_fullurl);
    $cur_url = $break[count($break) - 1];
//print $cur_url;

$cur_url = slfURL();

//echo $file;
    // print "current file : $cur_file <br>";           //current file name gets stored in $file

    $purl = (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');        //previous page url
    $break = Explode('/', $purl);
    $prev_url = $break[count($break) - 1];   //previous page file name with value passed to it

   
$prev_url =$purl ;


    if (isset($user)) {
        $user_key=$user->id;
    } else {
        $user_key=0;
    }

    $page_key=0;
    $date=date("Y-m-d H:i:s");


    if (isset($_SESSION['prev_page_key']))
        $prev_page_key=$_SESSION['prev_page_key'];
    else
        $prev_page_key=0;


    $sql1=sprintf("INSERT INTO `User Click Dimension` (

                  `User Key` ,
                  `User Log Key`,
                  `URL` ,
                  
                  `Page Key` ,
                  `Date` ,
                  `Previous Page` ,
                  `Session Key` ,
                  `Previous Page Key`
                  )
                  VALUES (
                  %d,%s,%s, %d,%s, %s, %d,%d
                  );",
                  $user_key,
                  prepare_mysql($user_log_key),
                  prepare_mysql($cur_url),
                  $page_key,
                  prepare_mysql($date),
                  prepare_mysql($prev_url),
                  $session_key,
                  $prev_page_key
                 );

   // print($sql1);
    mysql_query($sql1);
    $user_click_key= mysql_insert_id();



}
function update_page_key_visit_log($page_key) {
    global $user_click_key;
    $sql=sprintf("update `User Click Dimension`  set `Page Key`=%d where `User Click Key`=%d",$page_key,$user_click_key);
    mysql_query($sql);
}



function see_also($code, $url="http://www.ancientwisdom.biz/forms/") {
    global $store_key;
    $family=new LightFamily($code, $store_key);
    if (!$family->id)
        return;
    return $family->get_see_also($code, $url);
}

?>