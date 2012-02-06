<?php


require_once 'app_files/db/dns.php';

$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}

$site_key=3;

$url=$_SERVER['REQUEST_URI'];
$original_url=$url;
$url=preg_replace('/^\//', '', $url);
//$url=preg_replace('/\/$/', '', $url);

$slashes=explode("/", $url);
array_pop($slashes);
$path='';
foreach ($slashes as $i) {
	$path.='../';
}
preg_match_all('/\//', $url, $matches);
$found=false;

if ($page_key=get_page_key_from_url($url)) {
	//  print $url;
	header("Location: {$path}page.php?id=".$page_key."&url=".$original_url);
	exit;
}

if (preg_match('/\.php$/',$url)) {
	$url=preg_replace('/\.php$/','.html',$url);
	if ($page_key=get_page_key_from_url($url)) {

		header("Location: {$path}page.php?id=".$page_key."&url=".$original_url);
		exit;
	}

}elseif (preg_match('/\.html$/',$url)) {
	$url=preg_replace('/\.html$/','.php',$url);
	if ($page_key=get_page_key_from_url($url)) {

		header("Location: {$path}page.php?id=".$page_key."&url=".$original_url);
		exit;
	}

}else {

	$url=preg_replace('|\/$|','',$url);

	$url=$url.'/index.php';
	if ($page_key=get_page_key_from_url($url)) {

		header("Location: {$path}page.php?id=".$page_key."&url=".$original_url);
		exit;
	}

$url=$url.'/index.html';
	if ($page_key=get_page_key_from_url($url)) {

		header("Location: {$path}page.php?id=".$page_key."&url=".$original_url);
		exit;
	}

}





exit;



function get_page_key_from_url($code) {
global $site_key;
$page_key=0;
$sql=sprintf("select PS.`Page Key` from `Page Store Dimension` PS left join `Page Dimension` P on (PS.`Page Key`=P.`Page Key`) where `Page Site Key`=%d and `Page URL`=%s ",
	$site_key,
	prepare_mysql($code));
//print "$sql\n";
$res=mysql_query($sql);
if ($row=mysql_fetch_assoc($res)) {
	$page_key=$row['Page Key'];
}
//print $sql;
return $page_key;
}


function prepare_mysql($string,$null_if_empty=true) {

if (is_numeric($string)) {
	return "'".$string."'";
}
elseif ($string=='' and $null_if_empty) {
	return 'NULL';
}
else {
	return "'".addslashes($string)."'";


}
}



//  print "not found";






?>
