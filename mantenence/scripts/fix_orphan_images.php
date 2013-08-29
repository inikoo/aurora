<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.DeliveryNote.php';
include_once '../../class.Order.php';
include_once '../../class.Image.php';
include_once '../../class.Page.php';
include_once '../../class.Image.php';

include_once '../../class.Customer.php';

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';


$sql=sprintf("update `Image Dimension` set `Image Large Data`=NULL;");
mysql_query($sql);

$sql=sprintf("select * from `Page Store Dimension` order by `Page Site Key`,`Page Code`");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

	$found=false;
	$page=new Page($row['Page Key']);
	$html=$page->data['Page Store Source'];

	$regexp = "public_image.php\?id=\d+(\"|\')";

	$missing_image='';
	$missing_image_array=array();
	if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
		//print_r($matches);
		foreach ($matches as $match) {
			$_image_key=preg_replace('/[^\d]/','',$match[0]);

			$image=new Image($_image_key);
			if ($image->id) {
				$sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`) values ('Page',%d,%d) ",
					$page->id,
					$_image_key
				);
				//print "$sql\n";
				mysql_query($sql);
			}else {
				$missing_image='x';
				$missing_image_array[$_image_key]=$_image_key;
			}

		}
	}
	if ($missing_image!='')
		//print "image ".join(",",$missing_image_array)." not found in ".$page->data['Page URL']." \n";
		print $page->data['Page URL']." \n";

}




$sql=sprintf("select `Image Key`  from `Image Dimension` ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

	$sql2=sprintf("select count(*) as num from `Image Bridge` where `Image Key`=%d",
		$row['Image Key']
	);

	$res2=mysql_query($sql2);

	if ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
		if ($row2['num']==0) {
			$sql=sprintf("delete from `Image Dimension` where `Image Key`=%d ",
				$row['Image Key']
			);
			mysql_query($sql);
		}
	}

}

// delete not original pic data from Database



$sql=sprintf("select *  from `Image Bridge` where `Subject Type`='Page' group by `Subject Key`");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$page=new Page($row['Subject Key']);
	if (!$page->id) {
		print $row['Subject Key']."\n";


		$images=array();
		$sql2=sprintf("select `Image Key` from `Image Bridge` where `Subject Type`='Page' and `Subject Key`=%d",$row['Subject Key']);
		$res2=mysql_query($sql2);
		while ($row2=mysql_fetch_assoc($res2)) {
			$images[]=$row2['Image Key'];
		}
		$sql=sprintf("delete from  `Image Bridge` where `Subject Type`='Page' and `Subject Key`=%d",$row['Subject Key']);
		mysql_query($sql);

		foreach ($images as $image_key) {
			$image=new Image($image_key);
			$image->delete();
			if (!$image->deleted)
				$image->update_other_size_data();

		}


	}

}

$sql=sprintf("select `Image Key` from `Image Dimension`");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$image=new Image($row['Image Key']);
	$image->update_other_size_data();
}


$sql=sprintf("select *  from `Image Bridge` where `Subject Type`='Page' ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	//print $row['Image Key']." * ".$row['Subject Key']."\n";
	$image_key=$row['Image Key'];
	$found=false;
	$page=new Page($row['Subject Key']);
	$html=$page->data['Page Store Source'];

	$regexp = "public_image.php\?id=\d+(\"|\')";
	if (preg_match_all("/$regexp/siU", $html, $matches, PREG_SET_ORDER)) {
		//print_r($matches);
		foreach ($matches as $match) {
			$_image_key=preg_replace('/[^\d]/','',$match[0]);
			if ($_image_key==$image_key) {
				$found=true;
				break;
			}
		}
	}

	if (!$found) {
		$sql=sprintf("delete from `Image Bridge` where `Subject Type`='Page' and `Image Key`=%d and `Subject Key`=%d",
		$image_key,
		$row['Subject Key']
		
		);
		mysql_query($sql);
		//print "$sql\n";

		$image=new Image($row['Image Key']);
		$image->delete();
		if (!$image->deleted) {
			$image->update_other_size_data();
			print "tring deleting image ".$row['Image Key']." from page ".$row['Subject Key']."\n";

		}else {
			print "deleting image ".$row['Image Key']." from page ".$row['Subject Key']."\n";

		}
		
		
	}

}


?>
