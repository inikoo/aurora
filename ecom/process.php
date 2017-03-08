<?php
error_reporting(E_ALL ^ E_DEPRECATED);





require_once 'conf/conf.php';
require_once 'conf/dns.php';
include 'conf/timezone.php';
$mem = new Memcached();
$mem->addServer($memcache_ip, 11211);

$result=$mem->get('ECOMP'.md5(INIKOO_ACCOUNT.SITE_KEY.$_SERVER['REQUEST_URI']));
$result=false;
if (!$result ) {
	$result=get_url(SITE_KEY,$_SERVER['REQUEST_URI']);
	$mem->set('ECOMP'.md5(INIKOO_ACCOUNT.SITE_KEY.$_SERVER['REQUEST_URI']), $result, 172800);
}



if (is_numeric($result)) {
	$page_key=$result;
	include 'conf/dns.php';
	include 'common.php';
	include 'page.php';
	exit;
}else {

	if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') {
		$protocol='https';
	}else {
		$protocol='http';
	}
	header("Location: $protocol://$result");
	exit;

}



function get_url($site_key,$url) {
	global $dns_host,$dns_user,$dns_pwd ,$dns_db;
	date_default_timezone_set(TIMEZONE) ;

	
	
	$default_DB_link=mysql_connect($dns_host,$dns_user,$dns_pwd );
	if (!$default_DB_link) {
		print "Error can not connect with database server $dns_host,$dns_user\n";
		exit;
	}
	$db_selected=mysql_select_db($dns_db, $default_DB_link);
	if (!$db_selected) {
		print "Error can not access the database\n";
		exit;
	}

	$url=preg_replace('/^\//', '', $url);
	$url=preg_replace('/\?.*$/', '', $url);



	$original_url=$url;
	$page_key=get_page_key_from_code($site_key,$url);
	if ($page_key) {
		return $page_key;
	}

	if (!$page_key and preg_match('/[a-z0-9\_\-]\/$/i',$url)) {
		$_tmp_url=preg_replace('/\/$/','',$url);
		$page_key=get_page_key_from_code($site_key,$_tmp_url);
		if ($page_key) {
			return $page_key;

			//$url=$_SERVER['SERVER_NAME'].'/'.$_tmp_url;
			//return $url;
		}
	}





	if (preg_match('/[a-z0-9\_\-]\/$/i',$url)) {
		return $_SERVER['HTTP_HOST'].'/index.php?error='.$_tmp_url;
		//$_tmp_url=preg_replace('/\/$/','',$url);
		//exit("$_tmp_url");
		//header("Location: http://".$target);
	}
	$sql=sprintf("select `Site URL` from `Site Dimension` where `Site Key`=%d ",
		$site_key
	);
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
	$file=array_pop($url_array);
	if (preg_match('/\.(php|html)$/',$file)) {
		$path=join('/',$url_array);
	}else {
		$file='index.php';
		$path=$url;
	}

	$path=preg_replace('/\/$/','',$path);
		
	if (preg_match('/^sitemap\.xml$/',$path,$match)) {
		
		return $_SERVER['HTTP_HOST'].'/sitemap_index.xml.php';

	}
		
	if (preg_match('/^sitemap(\d+)\.xml$/',$path,$match)) {
		$sitemap_key=$match[1];
		return $_SERVER['HTTP_HOST'].'/sitemap.xml.php?id='.$sitemap_key;

	}
	$sql=sprintf("select  `Page Target URL` from `Page Redirection Dimension` where `Source Host`=%s and `Source Path`=%s and `Source File`=%s ",
		_prepare_mysql($site_url),
		_prepare_mysql($path,false),
		_prepare_mysql($file));
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$target=$row['Page Target URL'];
		return $target;
		//$new_url='ter:'.$target." $sql";
		//header("Location: http://".$target);
	}else {
		return  $site_url."/404.php?path=$path&f=$file&url=$url&original_url=$original_url";

		//header("Location: http://".$site_url."/404.php?path=$path&f=$file&url=$url&original_url=$original_url");
	}


}

function get_page_key_from_code($site_key,$code) {

	$page_key=0;
	$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d and `Page Code`=%s ",
		$site_key,
		_prepare_mysql($code));

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


?>
