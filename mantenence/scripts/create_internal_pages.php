<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Page.php');
include_once('../../class.Store.php');
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';           

global $myconf;




$data=array(
	     array(
		   'Page Type'=>'Internal'
		   ,'Page Title'=>'Sales Activity Report'
		   ,'Page Short Title'=>'Sales Activity'
		   ,'Page Description'=>'Quick snapshoot of the latest sales figures and the compation of these with previous data'
		   ,'Page URL'=>'report_sales_activity.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Activity/Performance Reports'
		   )
	     ,array(
		   'Page Type'=>'Internal'
		   ,'Page Title'=>'Sales Overview Report'
		   ,'Page Short Title'=>'Sales Overview'
		   ,'Page Description'=>''
		   ,'Page URL'=>'report_sales_main.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Sales Reports'
		   )
		  ,array(
		   'Page Type'=>'Internal'
		   ,'Page Title'=>'Geographic Sales Report'
		   ,'Page Short Title'=>'Geographic Sales'
		   ,'Page Description'=>''
		   ,'Page URL'=>'report_geo_sales.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Sales Reports'
		   )
	     ,array(
		   'Page Type'=>'Internal'
		   ,'Page Title'=>'Reporte Modelo 347 (ES)'
		   ,'Page Short Title'=>'Modelo 347'
		   ,'Page Description'=>''
		   ,'Page URL'=>'report_tax_ES1.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Tax Reports'
		   ,'Page Activated'=>'No'
		    )
	     ,array(
		    'Page Type'=>'Internal'
		   ,'Page Title'=>'Pickers & Packers Report'
		   ,'Page Short Title'=>'P&P Report'
		   ,'Page Description'=>''
		   ,'Page URL'=>'report_pp.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Activity/Performance Reports'
		   )
		     ,array(
		    'Page Type'=>'Internal'
		   ,'Page Title'=>'Marked as Out of Stock Report'
		   ,'Page Short Title'=>'Mark as Out of Stock'
		   ,'Page Description'=>''
		   ,'Page URL'=>'report_out_of_stock.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Activity/Performance Reports'
		   )
		   	     ,array(
		    'Page Type'=>'Internal'
		   ,'Page Title'=>'Customer First Order Analysis'
		   ,'Page Short Title'=>'First Order'
		   ,'Page Description'=>''
		   ,'Page URL'=>'report_first_order.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Sales Reports'
		   )
	       ,array(
		    'Page Type'=>'Internal'
		   ,'Page Title'=>'Top Customers Report'
		   ,'Page Short Title'=>'Top Customers'
		   ,'Page Description'=>''
		   ,'Page URL'=>'report_customers.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Activity/Performance Reports'
		   )
	     ,array(
		    'Page Type'=>'Internal'
		   ,'Page Title'=>'Invoices Without Tax Report'
		   ,'Page Short Title'=>'No Tax Report'
		   ,'Page Description'=>''
		   ,'Page URL'=>'report_sales_with_no_tax.php'
		   ,'Page Section'=>'Reports'
		   ,'Page Parent Category'=>'Tax Reports'
		   )
	     
	    );




foreach($data as $page_data){
  
  $page=new Page('find',$page_data,'create');
  //print_r($page);
  
}





?>