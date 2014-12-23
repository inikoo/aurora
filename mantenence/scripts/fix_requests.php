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


$sql=sprintf("select `Previous Page`,`Site Key`,`User Request Key` from `User Request Dimension`  where `User Request Referral Page URL Key` is NULL  and `Previous Page`!=''  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	//print_r($row);
	$page_url_key=get_page_url_key($row['Previous Page'],$row['Site Key']);
	
	$sql=sprintf("update `User Request Dimension` set `User Request Referral Page URL Key`=%d where `User Request Key`=%d",
	$page_url_key,
	$row['User Request Key']
	);
	mysql_query($sql);
}

function get_page_url_key($url,$site_key) {

	$url=trim($url);
	if ($url=='') {
		return 0;
	}

	$site=new Site($site_key);

	$sql=sprintf("select `Page URL Key` from `Page URL Dimension` where `Page URL`=%s  ",prepare_mysql($url));
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$page_url_key=$row['Page URL Key'];
	}else {


		$page_key=0;
		if (preg_match('|^https?\:\/\/'.$site->data['Site URL'].'\/page\.php\?id=(\d+)|',$url,$match)) {
			$page_key=$match[1];
		}
		if (preg_match('|^https?\:\/\/'.$site->data['Site URL'].'\/(.+)|',$url,$match)) {
			$page_code=$match[1];

			$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Site Key`=%d and `Page Code`=%s ",
				$site_key,
				prepare_mysql($page_code));

			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$page_key=$row['Page Key'];
			}


		}

		$sql=sprintf("insert into `Page URL Dimension` (`Page URL`,`Page Key`) values (%s,%d) ",
			prepare_mysql($url),
			$page_key

		);
		mysql_query($sql);
		$page_url_key=mysql_insert_id();

	}


	return $page_url_key;


}





?>
