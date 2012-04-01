<?php
include_once 'app_files/db/dns.php';
include_once 'class.Image.php';

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
setlocale(LC_MONETARY, 'en_GB.UTF-8');


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



$sql=sprintf("select UNIX_TIMESTAMP(`Last Modify Date`) as image_time from `Image Dimension` where `Image Key`=%d",$id);
$result2 = mysql_query($sql);
if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)) {

	/*
$tmp= 'Last-Modified: '.gmdate('D, d M Y H:i:s', $row['image_time']).' GMT';

   // header($tmp, true, 304);

	header('Content-type: image/'.$row['Image File Format']);
	header('Content-Disposition: inline; filename='.$row['Image Original Filename']);

	echo $row['data'];
	//readfile($row['Attachment Filename']);
	// echo  $row['Image Data'];
	// var_dump(  $row) ;

	//exit;
*/

	$image_time=$row2['image_time'];
	$send_304 = false;
	$sapi=php_sapi_name() ;
	if (in_array($sapi,array( 'apache','apache2handler','apache2filter'))) {
		$ar = apache_request_headers();
		if (isset($ar['If-Modified-Since']) && // If-Modified-Since should exists
			($ar['If-Modified-Since'] != '') && // not empty
			(strtotime($ar['If-Modified-Since']) >= $image_time)) // and grater than
			$send_304 = true;                                     // image_time
	}


	if ($send_304) {
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $image_time).' GMT', true, 304);
		exit();
	}


	$sql=sprintf("select $image_data ,`Image Original Filename`,`Image File Format` from `Image Dimension` where `Image Key`=%d",$id);
	$result = mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $image_time).' GMT',true, 200);
		header('Expires: '.gmdate('D, d M Y H:i:s',  $image_time + 86400*365).' GMT',true, 200);
		header('Content-Length: '.strlen($row['data']));
		header('Content-type: image/'.$row['Image File Format']);
		header('Content-Disposition: inline; filename='.$row['Image Original Filename']);
		echo $row['data'];
		exit();


	}
}
else {



}

?>
