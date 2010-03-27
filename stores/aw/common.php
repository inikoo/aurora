<?php
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
mysql_query("SET time_zone='+0:00'");
require_once 'conf/conf.php';   

$session = new Session($myconf['max_session_time'],1,100);
require('external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = $myconf['template_dir'];
$smarty->compile_dir = $myconf['compile_dir'];
$smarty->cache_dir = $myconf['cache_dir'];
$smarty->config_dir = $myconf['config_dir'];

$store_key=1;
$sql=sprintf("select `Product Department Code`,`Product Department Name` from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Sales Type`='Public Sale' ",$store_key);
$res=mysql_query($sql);
$departments=array();
while($row=mysql_fetch_array($res)){
  $departments[]=array('code'=>$row['Product Department Code'],'name'=>$row['Product Department Name']);
}

$smarty->assign('departments',$departments);

?>