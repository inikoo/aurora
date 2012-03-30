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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

chdir('../../');

//$sql="select * from kbase.`Country Dimension`";
//$result=mysql_query($sql);
//while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
//print "cp ../../examples/_countries/".strtolower(preg_replace('/\s/','_',$row['Country Name']))."/ammap_data.xml ".$row['Country Code'].".xml\n";
//}
//exit;


$site=new Site(1);



$sql="select * from `Page Store Dimension` PS  left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) where PS.`Page Key`=2205  ";

$sql="select `Source Path`,`Source Host` from `Page Redirection Dimension` R  left join `Page Store Dimension` P on (R.`Page Target Key`=P.`Page Key`) where `Page Site Key`=".$site->id." group by `Source Path` limit 100,1";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$host=$row['Source Host'];
	$path=$row['Source Path'];

	print "$host $path\n";
	

	$site->upload_redirections($host,$path);
exit;	
	

}




?>
