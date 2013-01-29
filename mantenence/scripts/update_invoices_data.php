<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Invoice.php';
include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

$sql="select * from `Invoice Dimension`";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {






	/*
  $id=$row['Invoice Key'];
 $total_costs=0;
  $sql=sprintf("select ifnull(sum(`Cost Supplier`/`Invoice Currency Exchange Rate`),0) as `Cost Supplier`  ,ifnull(sum(`Cost Manufacure`/`Invoice Currency Exchange Rate`),0) as `Cost Manufacure` ,ifnull(sum(`Cost Storing`/`Invoice Currency Exchange Rate`),0) as `Cost Storing`,ifnull(sum(`Cost Handing`/`Invoice Currency Exchange Rate`),0)  as  `Cost Handing`,ifnull(sum(`Cost Shipping`/`Invoice Currency Exchange Rate`),0) as `Cost Shipping` from `Order Transaction Fact` where `Invoice Key`=%d",$id);

  $result2 = mysql_query ( $sql );
   if ($row2 = mysql_fetch_array ( $result2, MYSQL_ASSOC )) {
     $total_costs=$row2['Cost Supplier']+$row2['Cost Manufacure']+$row2['Cost Storing']+$row2['Cost Handing']+$row2['Cost Shipping'];

   }
   $profit= $row['Invoice Total Net Amount']- $row['Invoice Refund Net Amount']-$total_costs;


   $sql=sprintf("update `Invoice Dimension` set `Invoice Total Profit`=%f where `Invoice Key`=%d " ,$profit,$id);
   print "$total_costs\n";
 mysql_query($sql);
*/




	$invoice=new Invoice($row['Invoice Key']);

	$customer=new Customer($row['Invoice Customer Key']);



	$data=array();
	if ($customer->id) {

		$data['Invoice For Partner']='No';
			$data['Invoice For']='Customer';

		switch ($customer->data['Customer Level Type']) {
		case'Partner':
			$data['Invoice For Partner']='Yes';
			break;
		case'Staff':
			$data['Invoice For']='Staff';
			break;

		}

		$data['Invoice Customer Level Type']=$customer->data['Customer Level Type'];
		
		$sql=sprintf("update `Invoice Dimension` set  `Invoice For Partner`=%s,`Invoice For`=%s,`Invoice Customer Level Type`=%s where `Invoice Key`=%d",
		prepare_mysql($data['Invoice For Partner']),
		prepare_mysql($data['Invoice For']),
		prepare_mysql($data['Invoice Customer Level Type']),
		$invoice->id
		);
//print $sql;
		mysql_query($sql);

	}

	// print $invoice->id."\n";
	$invoice->get_data('id',$invoice->id);
	$invoice->categorize();
	print $invoice->id."\r";
	//$force_values=array(
	// 'Invoice Items Net Amount'=>$invoice->data['Invoice Items Net Amount']
	//       ,'Invoice Total Net Amount'=>$invoice->data['Invoice Total Net Amount']
	//       ,'Invoice Total Tax Amount'=>$invoice->data['Invoice Total Tax Amount']
	//       ,'Invoice Total Amount'=>$invoice->data['Invoice Total Amount']
	//       );
	// print_r($force_values);
	// $invoice->get_totals();
	// $invoice->get_totals($force_values);
}





?>
