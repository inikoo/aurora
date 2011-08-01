<?php

$path = 'classes/';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'app_files/db/dns.php';
require_once 'common_functions.php';
require_once "class.Session.php";
require_once "class.Auth.php";
require_once "class.User.php";
require_once "class.Site.php";



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
require('external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();


$site=new Site($myconf['site_key']);
if(!$site->id){

print_r($site);
exit ("Site data not found (".$myconf['site_key'].')<br/> click <a href="../mantenence/scripts/create_sites.php">here to create one</a> ');
}
$page_data=$site->get_data_for_smarty();



$store_key=$site->data['Site Store Key'];
$store=new Store($store_key);
$store_code=$store->data['Store Code'];
$smarty->assign('store_code',$store_code);
$smarty->assign('store_key',$store_key);

$_client_locale=$store->data['Store Locale'].'.UTF-8';
setlocale(LC_MONETARY, $_client_locale);


$traslated_labels=array();

if (file_exists($store_code.'/labels.php')) {
    require_once $store_code.'/labels.php';
} else {
    require_once 'conf/labels.php';
}

$smarty->assign('traslated_labels',$traslated_labels);

$smarty->template_dir = '.';
$smarty->compile_dir ='server_files/smarty/templates_c';
$smarty->cache_dir = 'server_files/smarty/cache';
$smarty->config_dir = 'server_files/smarty/configs';

$_SESSION ['lang']='';

$logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);

if ($logged_in ) {

    if ($_SESSION['logged_in_page']!=$store_key) {


        include_once('app_files/key.php');

        include_once('aes.php');
        $Sk="skstart|".(date('U')+300)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
        $St=AESEncryptCtr($Sk,SKEY, 256);
        $smarty->assign('secret_string',$St);
        $logged_in=0;
        $_SESSION['logged_in']=0;

    } else {


        if (!isset($_SESSION['state'])) {
            include_once('conf/state.php');
            $_SESSION['state']=$default_state;
        }

        $user=new User($_SESSION['user_key']);
        $smarty->assign('user',$user);
        if (isset($_SESSION['order_data']))
            $smarty->assign('order',$_SESSION['order_data']);



    }

} else {




    include_once('app_files/key.php');
    //$auth=new Auth(IKEY,SKEY);



    include_once('aes.php');
    $Sk="skstart|".(date('U')+300)."|".ip()."|".IKEY."|".sha1(mt_rand()).sha1(mt_rand());
    $St=AESEncryptCtr($Sk,SKEY, 256);
    $smarty->assign('secret_string',$St);
}


log_visit($session->id);


$smarty->assign('logged_in',$logged_in);



$smarty->assign('head_template',"templates/head.".$store->data['Store Locale'].".tpl");
$smarty->assign('footer_template',"templates/footer.".$store->data['Store Locale'].".tpl");
$smarty->assign('main_menu_template',"templates/main_menu.tpl");

$smarty->assign('email',$store->data['Store Email']);
$smarty->assign('tel',$store->data['Store Telephone']);
$smarty->assign('fax',$store->data['Store Fax']);
$smarty->assign('store_slogan',$store->data['Store Slogan']);




$sql=sprintf("select `Page Section`,`Page Short Title`,`Page URL` from `Page Dimension` P  left join `Page Store Dimension` PS on (PS.`Page Key`=P.`Page Key`) where `Page Type`='Store' and `Page Parent Key`=%d and `Page Store Section`='Information'   ",$store_key);

$info_pages=array();
$inspiration_pages=array();
$incentive_pages=array();
$res=mysql_query($sql);

while ($row=mysql_fetch_array($res)) {
    if ($row['Page Section']=='info')
        $info_pages[]=array('url'=>$row['Page URL'],'short_title'=>$row['Page Short Title']);
    elseif($row['Page Section']=='inspiration')
    $inspiration_pages[]=array('url'=>$row['Page URL'],'short_title'=>$row['Page Short Title']);
    elseif($row['Page Section']=='incentives')
    $incentive_pages[]=array('url'=>$row['Page URL'],'short_title'=>$row['Page Short Title']);

}
$smarty->assign('info_pages',$info_pages);
$smarty->assign('incentive_pages',$incentive_pages);
$smarty->assign('inspiration_pages',$inspiration_pages);



$sql=sprintf("select `Product Department Code`,`Product Department Name` from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Sales Type`='Public Sale' ",$store_key);
$res=mysql_query($sql);
$departments=array();
while ($row=mysql_fetch_array($res)) {
    $departments[]=array('code'=>$row['Product Department Code'],'name'=>$row['Product Department Name']);
}








$smarty->assign('departments',$departments);

function log_visit($session_key) {


global $user_click_key;
$user_click_key=0;
   // $file = $_SERVER["SCRIPT_NAME"]; //current file path gets stored in $file
$file = $_SERVER["PHP_SELF"];
//echo $file;
 
 $break = Explode('/', $file);
    $cur_file = $break[count($break) - 1];
if(preg_match('/^ar\_/',$cur_file)){
    return;
}

if(preg_match('/^ar_/',$cur_file) or preg_match('/\.js/',$cur_file)){
return;
}

// function to get the full url of the current page
function slfURL() 
{ $s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
 $protocol = strleft1(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s; $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]); 
return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI']; 
}

 function strleft1($s1, $s2) 
{ return substr($s1, 0, strpos($s1, $s2)); }

$cur_fullurl=slfURL();
//print "$cur_fullurl<br>";
$break = Explode('/', $cur_fullurl);
$cur_url = $break[count($break) - 1];
//print $cur_url;



//echo $file;
   // print "current file : $cur_file <br>";           //current file name gets stored in $file

    $purl = (isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'');        //previous page url
    $break = Explode('/', $purl);
    $prev_url = $break[count($break) - 1];   //previous page file name with value passed to it

    //$pos = strpos($prev_url, '?');

    //$prev_file = substr($prev_url,0, $pos);
   // print "previous file : $prev_file<br>";
//echo("<br>");



    if (isset($user)) {
        $user_key=$user->id;
    } else {
        $user_key=0;
    }

    $page_key=0;
    $date=date("Y-m-d H:i:s");
   
   
   if(isset($_SESSION['prev_page_key']))
    $prev_page_key=$_SESSION['prev_page_key'];
  else
  $prev_page_key=0;
  
  
        $sql1=sprintf("INSERT INTO `User Click Dimension` (

                      `User Key` ,
                      `URL` ,
                      `Page Key` ,
                      `Date` ,
                      `Previous Page` ,
                      `Session Key` ,
                      `Previous Page Key`
                      )
                      VALUES (
                      %d,%s, %d,%s, %s, %d,%d
                      );",
                      $user_key,
                      prepare_mysql($cur_url),
                      $page_key,
                      prepare_mysql($date),
                      prepare_mysql($prev_url),
                      $session_key,
                      $prev_page_key
                     );

        //print($sql1);
        mysql_query($sql1);
        $user_click_key= mysql_insert_id();

  

}
function update_page_key_visit_log($page_key){
    global $user_click_key;
    $sql=sprintf("update `User Click Dimension`  set `Page Key`=%d where `User Click Key`=%d",$page_key,$user_click_key);
    mysql_query($sql);

}


?>
