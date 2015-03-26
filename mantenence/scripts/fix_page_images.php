<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Customer.php';
include_once '../../class.Site.php';
include_once '../../class.Image.php';

error_reporting(E_ALL);




date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
require_once '../../common_detect_agent.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';


$sql=sprintf("select`Page Store Section Type`,`Page Parent Key`,`Page Store Key`,`Page Store Image Key`,P.`Page Key`,`Page Store Section` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store' ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$page=new Page($row['Page Key']);

	$page_image=new Image($row['Page Store Image Key']);
	$image_key=false;
	if ($page_image->id) {
		$page_image_source=sprintf("images/%07d.%s",$page_image->data['Image Key'],$page_image->data['Image File Format']);
		$image_key=$page_image->id;
		$sql=sprintf("insert into `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`) values ('Page',%d,%d)",
			$page->id,
			$page_image->id
			);
		mysql_query($sql);
		//print "$sql\n";
	}else {

		if($row['Page Store Section Type']=='Product'){
			$product=new Product('pid',$row['Page Parent Key']);
			if($product->id and $product->data['Product Main Image Key']){
				$_page_image=new Image($product->data['Product Main Image Key']);
				if ($_page_image->id) {
					$page_image_source=sprintf("images/%07d.%s",$_page_image->data['Image Key'],$_page_image->data['Image File Format']);
					$image_key=$_page_image->id;
				}
			}




		}else{
			$page_image_source='art/nopic.png';
		}

		

	}
	unset($page_image);
	$sql=sprintf("update `Page Store Dimension` set `Page Store Image URL`=%s where `Page Key`=%d",
		prepare_mysql($page_image_source),
		$page->id
		);
	//print "$sql\n";
	mysql_query($sql);

	if($image_key){
		$sql=sprintf("update `Page Store Dimension` set `Page Store Image Key`=%d where `Page Key`=%d",
			$image_key,
			$page->id
			);
		//	print "$sql\n";
		mysql_query($sql);

	}




	



}





?>
