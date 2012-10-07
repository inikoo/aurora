<?php
require_once 'app_files/db/dns.php';
include_once('app_files/key.php');
require_once 'class.Session.php';
require_once 'class.Auth.php';
require_once 'common_functions.php';

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
//$max_session_time=1000000;
//$session = new Session($max_session_time,1,100);
session_start();
$logged_in=(isset($_SESSION['logged_in']) and $_SESSION['logged_in']? true : false);



$auth=new Auth(IKEY,SKEY);

//$auth->unset_cookies();
$sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
mysql_query($sql);


//session_regenerate_id();
//session_destroy();
//unset($_SESSION);



$_SESSION = array();


if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie('sk', '', time() - 42000,
              $params["path"], $params["domain"],
              $params["secure"], $params["httponly"]
             );
    setcookie('page_key', '', time() - 42000,
              $params["path"], $params["domain"],
              $params["secure"], $params["httponly"]
             );
    $resxx=setcookie('user_handle', '', time() - 42000,
                     $params["path"], $params["domain"],
                     $params["secure"], $params["httponly"]
                    );

    //print "xxx $resxx xxx";

}


session_destroy();



// include_once 'login.php';
// exit;

$_SESSION['logged_in']=0;


session_regenerate_id();


header('location: login.php');
exit;
/*
if ($logout) {
	$auth=new Auth(IKEY,SKEY);

	//$auth->unset_cookies();
	$sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW()  where `Session ID`=%s", prepare_mysql(session_id()));
	mysql_query($sql);


	//session_regenerate_id();
	//session_destroy();
	//unset($_SESSION);



	$_SESSION = array();


	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();

		setcookie('sk', '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
		setcookie('page_key', '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
		$resxx=setcookie('user_handle', '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);

	}


	session_destroy();
	$_SESSION['logged_in']=0;
	session_regenerate_id();


	$logged_in=false;
	$St=get_sk();
}
*/

?>