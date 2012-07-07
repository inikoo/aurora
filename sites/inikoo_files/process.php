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

//print_r($_SERVER);
//exit;
require_once 'conf/conf.php';
$site_key=$myconf['site_key'];

$url=$_SERVER['REQUEST_URI'];



	
//print_r($_SERVER);

$url=preg_replace('/^\//', '', $url);


$url=preg_replace('/\?.*$/', '', $url);


	$original_url= $url;

if ($page_key=get_page_key_from_code($site_key,$url)) {
	include_once 'common.php';
	include_once 'page.php';


}else {



	$sql=sprintf("select `Site URL` from `Site Dimension` where `Site Key`=%d ",
		$site_key
	);
	//print "$sql\n";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$site_url=$row['Site URL'];
	}else {
		exit("error A");
	}

$original_url=$site_url.'/'.$original_url;

	$path='';
	$file='';
	$url_array=explode("/", $url);
	//print_r($url_array);

	$file=array_pop($url_array);
	if (preg_match('/\.(php|html)$/',$file)) {
		$path=join('/',$url_array);
	}else {
		$file='index.php';
		$path=$url;

	}

	$path=preg_replace('/\/$/','',$path);




	$sql=sprintf("select  `Page Target URL` from `Page Redirection Dimension` where `Source Host`=%s and `Source Path`=%s and `Source File`=%s ",_prepare_mysql($site_url),_prepare_mysql($path,false),_prepare_mysql($file));


	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$target=$row['Page Target URL'];
		$new_url='ter:'.$target." $sql";
		header("Location: http://".$target);
	}else {
		$new_url=$site_url."/404.php?p=$path&f=$file&url=$url&original_url=$original_url";
		header("Location: http://".$site_url."/404.php?path=$path&f=$file&url=$url&original_url=$original_url");
	}





	exit();
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
