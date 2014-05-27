<?php
require_once 'app_files/db/dns.php';
include_once 'app_files/key.php';
require_once 'class.Session.php';
require_once 'class.Auth.php';
require_once 'class.User.php';
require_once 'class.Customer.php';

require_once 'common_functions.php';
require_once 'common_detect_agent.php';

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
if (!$logged_in) {
	header('location: login.php');
	exit();
}

//$_SESSION['offset']='Europe/Warsaw';
date_default_timezone_set($_SESSION['offset']);
require_once 'conf/conf.php';


$auth=new Auth(IKEY,SKEY);
$sql=sprintf("update `User Log Dimension` set `Logout Date`=NOW() ,`Status`='Close' where `Session ID`=%s", prepare_mysql(session_id()));
mysql_query($sql);
//print_r($_SESSION);

$user=new User($_SESSION['user_key']);


$customer=new Customer($_SESSION['customer_key']);

$date=gmdate('Y-m-d H:i:s');
$details='<table>
				<tr><td style="width:120px">'._('Time').':</td><td>'.strftime("%c %Z",strtotime($date.' +00:00')).'</td></tr>
				<tr><td>'._('IP Address').':</td><td>'.ip().'</td></tr>
				<tr><td>'._('User Agent').':</td><td>'.$_SERVER['HTTP_USER_AGENT'].'</td></tr>
				</table>';

$history_data=array(
	'Date'=>$date,
	'Site Key'=>$myconf['site_key'],
	'Note'=>_('Logout'),
	'Details'=>$details,
	'Action'=>'logout',
	'Indirect Object'=>'',
	'User Key'=>$user->id
);

$customer->add_history_login($history_data);


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
header('location: login.php');



?>
