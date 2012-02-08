<?php
include_once('common_functions.php');
include_once('app_files/db/key.php');

include_once('aes.php');
$path = 'common';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);
require_once 'app_files/db/dns.php';         // DB connecton configuration file
require_once 'MDB2.php';            // PEAR Database Abstraction Layer





require_once 'LiveUser.php';        // PEAR Authentication System
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
require_once 'myconf/conf.php';  
require('/usr/share/php/smarty/Smarty.class.php');
$smarty = new Smarty();

$smarty->template_dir = $myconf['template_dir'];
$smarty->compile_dir = $myconf['compile_dir'];
$smarty->cache_dir = $myconf['cache_dir'];
$smarty->config_dir = $myconf['config_dir'];


$LU = LiveUser::singleton($LU_conf);
if (!$LU->init()) {
  var_dump($LU->getErrors());
  die('');
 }
if (!$LU) 
  die(_('An unknown error occurred'));



$handle = (array_key_exists('_login_', $_REQUEST)) ? $_REQUEST['_login_'] : null;
$sk = (array_key_exists('ep', $_REQUEST)) ? $_REQUEST['ep'] : null;



$sql=sprintf("select passwd from liveuser_users where handle='%s'",addslashes($handle));
$res=mysql_query($sql);
if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $pwd=$row['passwd'];
  
  $st=AESDecryptCtr(AESDecryptCtr($sk,$pwd,256),SKEY,256);
  if(preg_match('/^skstart\|\d+\|[0-9\.]+\|/',$st)){
    $data=preg_split('/\|/',$st);
    $time=$data[1];
    $ip=$data[2];
    if($time-date('U')>0 and ip()==$ip  ){
       $LU->login($handle, $pwd, true);
        if ($LU->isLoggedIn()){
	  $sql="insert into session (user_id,ip,start,last) values (".$LU->getProperty('auth_user_id').",'".ip()."',NOW(),NOW())";
	  mysql_query($sql);
	  $session_id=mysql_insert_id();
	  $_SESSION['mysession_id']=$session_id;

	  header('index.php');
	  print "hola";
	  exit;
	}

    }
      
  }
  
 }

include_once 'login.php';
exit;

?>