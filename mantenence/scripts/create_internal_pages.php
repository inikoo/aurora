<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.Image.php');
include_once('../../class.Page.php');

date_default_timezone_set('Europe/London');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
require_once '../../class.User.php';

mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           

$data=array();
$data[]=array(
	    'Page Type'=>'Internal'
	    ,'Page Title'=>'Sales by Period Reports'
	    ,'Page Short Title'=>'Sales by Period'
	    ,'Page URL'=>'report_sales.php'
	    ,'Page Description'=>'The sales reporting includes the key performance indicators of the sales force froupd by Store/Sub-store categories durinf a determinted period of time. The Key Performance Indicators indicate whether or not the sales process is being operated effectively and achieves the results as set forth in sales planning. It should enable the sales managers to take timely corrective action deviate from projected values. It also allows senior management to evaluate the sales manager.'
	    ,'Page Parent Category'=>'Sales Reports'
	    ,'Page Section'=>'Reports'
	      );
$data[]=array(
	    'Page Type'=>'Internal'
	    ,'Page Title'=>'Sales Activity Report'
	    ,'Page Short Title'=>'Sales Activity'
	    ,'Page URL'=>'report_sales_activity.php'
	    ,'Page Description'=>'Quick snapshoot of the latest sales figures and the compation of these with previous data, '
	    ,'Page Parent Category'=>'Activity/Performace Reports'
	    ,'Page Section'=>'Reports'
	      );


foreach($data as $page_data){
  print_r($page_data);
  $page=new Page('find',$page_data,'create');
}


?>