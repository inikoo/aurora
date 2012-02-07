<?php
require_once 'app_files/db/dns.php';
require_once 'conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;
$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
require_once 'conf/conf.php';
$site_key=$myconf['site_key'];

$url=$_SERVER['REQUEST_URI'];
$url=preg_replace('/^\//', '', $url);
if ($page_key=get_page_key_from_code($site_key,$url)) {
include_once('common.php');
	include_once 'page.php';


}else {
	//header("Location: 404.php");
	print "no found $url";
	exit;
}






function get_page_key_from_code($site_key,$code) {

	$page_key=0;
	$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d and `Page Code`=%s ",
		$site_key,
		_prepare_mysql($code));
	//print "$sql\n";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$page_key=$row['Page Key'];
	}
	//print $sql;
	return $page_key;
}


function _prepare_mysql($string,$null_if_empty=true) {

	if ($string=='' and $null_if_empty) {
		return 'NULL';
	}
	else {
		return "'".addslashes($string)."'";
	}
}



//  print "not found";






?>
