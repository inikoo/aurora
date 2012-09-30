<?php

$modify_since=false;
if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) and $_SERVER['HTTP_IF_MODIFIED_SINCE']!='') {
	$modify_since=$_SERVER['HTTP_IF_MODIFIED_SINCE'];

	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $modify_since).' GMT', true, 304);
	exit("xx");

}else {


	$sapi=php_sapi_name() ;
	if (in_array($sapi,array( 'apache','apache2handler','apache2filter'))) {

		$ar = apache_request_headers();
		if (isset($ar['If-Modified-Since']) && // If-Modified-Since should exists
			($ar['If-Modified-Since'] != '') ) {
			header('Last-Modified: '.gmdate('D, d M Y H:i:s', $ar['If-Modified-Since']).' GMT', true, 304);
		exit;
	}
	
	}

}


include_once 'app_files/db/dns.php';

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}
date_default_timezone_set('UTC');

require_once 'common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once 'conf/conf.php';


if (!isset($_REQUEST['id'])) {
	$id=-1;
}else
	$id=$_REQUEST['id'];


if (isset($_REQUEST['size']) and preg_match('/^large|small|thumbnail|tiny$/',$_REQUEST['size']))
	$size=$_REQUEST['size'];
else
	$size='original';


if ($size=='original') {
	$image_data='`Image Data` as data';
}elseif ($size=='large') {
	$image_data='iFNULL(`Image Large Data`,`Image Data`) as data';


}elseif ($size=='small') {
	$image_data='iFNULL(`Image Small Data`,`Image Data`) as data';


}elseif ($size=='thumbnail' or $size=='tiny') {
	$image_data='`Image Thumbnail Data` as data';

}else {
	$image_data='`Image Data` as data';

}


$sql=sprintf("select $image_data ,UNIX_TIMESTAMP(`Last Modify Date`) as image_time,`Image Original Filename`,`Image File Format`,`Image Key` from `Image Dimension` where `Image Key`=%d",
$id);
$result = mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

	$image_time=$row['image_time'];

	header('Last-Modified: '.gmdate('D, d M Y H:i:s', $image_time).' GMT',true, 200);
	header('Expires: '.gmdate('D, d M Y H:i:s',  $image_time + 86400*365).' GMT',true, 200);
	header('Content-Length: '.strlen($row['data']));
	header('Content-type: image/'.$row['Image File Format']);
	header('Content-Disposition: inline; filename="'.$sapi.'-'.$row['Image Key'].'.'.$row['Image File Format'].'"');
	header('Cache-Control: public, max-age=3600001, post-check=3600000, pre-check=3600000');

	echo $row['data'];
	exit();


}

else {
	header("HTTP/1.0 404 Not Found");
	exit();
}

?>
