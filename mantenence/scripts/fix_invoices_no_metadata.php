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
include_once '../../class.Invoice.php';

error_reporting(E_ALL);


date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
$_category_keys=array();

//$sql=sprintf("delete from `Category Bridge`  where   `Subject`='Invoice' ");
//mysql_query($sql);

$sql="select * from `Invoice Dimension` where `Invoice Metadata` is NULL ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$invoice=new Invoice ($row['Invoice Key']);

	$sql=sprintf("select * from `Order Dimension` where `Order Public ID`=%s  and `Order Original Metadata` is not NULL ",prepare_mysql($invoice->data['Invoice Public ID']));
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {


		$metadata=preg_replace('/[^\d]/','',$row['Order Original Metadata']);

		print "deleting  ".$row['Order Public ID']."  ".$row['Order Original Metadata']."  $metadata , invoice_id: ".$invoice->id."  \n";


		if (preg_match('/^U/',$row['Order Original Metadata'])) {
			$sql=sprintf("update orders_data.orders set last_transcribed=NULL where id=%d",$metadata);
			mysql_query($sql);
			print "$sql\n";
		}elseif (preg_match('/^D/',$row['Order Original Metadata'])) {
			$sql=sprintf("update de_orders_data.orders set last_transcribed=NULL where id=%d",$metadata);
			mysql_query($sql);
			print "$sql\n";
		}elseif (preg_match('/^F/i',$row['Order Original Metadata'])) {
			$sql=sprintf("update fr_orders_data.orders set last_transcribed=NULL where id=%d",$metadata);
			mysql_query($sql);
			print "$sql\n";
		}elseif (preg_match('/^I/i',$row['Order Original Metadata'])) {
			$sql=sprintf("update it_orders_data.orders set last_transcribed=NULL where id=%d",$metadata);
			mysql_query($sql);
			print "$sql\n";
		}elseif (preg_match('/^P/i',$row['Order Original Metadata'])) {
			$sql=sprintf("update pl_orders_data.orders set last_transcribed=NULL where id=%d",$metadata);
			mysql_query($sql);
			print "$sql\n";
		}


		

		$sql=sprintf("delete from `Order Invoice Bridge` where `Invoice Key`=%d   ",$invoice->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Tax Bridge` where `Invoice Key`=%d",$invoice->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Sales Representative Bridge`  where   `Invoice Key`=%d",$invoice->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Processed By Bridge`  where   `Invoice Key`=%d",$invoice->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Charged By Bridge`  where   `Invoice Key`=%d",$invoice->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Tax Dimension` where `Invoice Key`=%d",$invoice->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Delivery Note Bridge` where `Invoice Key`=%d   ",$invoice->id);
		mysql_query($sql)  ;

		$sql=sprintf("delete from `History Dimension`  where   `Direct Object`='Invoice' and `Direct Object Key`=%d",$invoice->id);
		mysql_query($sql);


		$sql=sprintf("select `Category Key` from `Category Bridge`  where   `Subject`='Invoice' and `Subject Key`=%d",$invoice->id);
		$result_test_category_keys=mysql_query($sql);
		
		while ($row_test_category_keys=mysql_fetch_array($result_test_category_keys, MYSQL_ASSOC)) {
			$_category_keys[]=$row_test_category_keys['Category Key'];
		}
		$sql=sprintf("delete from `Category Bridge`  where   `Subject`='Invoice' and `Subject Key`=%d",$invoice->id);
		mysql_query($sql);

		

		$sql=sprintf("delete from `Order No Product Transaction Fact` where `Invoice Key`=%d",$invoice->id);
		mysql_query($sql);



		$sql=sprintf("delete from `Order Transaction Fact` where `Invoice Key`=%d",$invoice->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Dimension` where `Invoice Key`=%d",$invoice->id);
	mysql_query($sql);


	}
	else{
		print $invoice->id.' '.$invoice->data['Invoice Public ID']." new system\n";
	}





}

$invoices_to_delete=array('1472267','1472265');

foreach($invoices_to_delete as $invoice_key){
		$sql=sprintf("delete from `Order Invoice Bridge` where `Invoice Key`=%d   ",$invoice_key);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Tax Bridge` where `Invoice Key`=%d",$invoice_key);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Sales Representative Bridge`  where   `Invoice Key`=%d",$invoice_key);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Processed By Bridge`  where   `Invoice Key`=%d",$invoice_key);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Charged By Bridge`  where   `Invoice Key`=%d",$invoice_key);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Tax Dimension` where `Invoice Key`=%d",$invoice_key);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Delivery Note Bridge` where `Invoice Key`=%d   ",$invoice_key);
		mysql_query($sql)  ;

		$sql=sprintf("delete from `History Dimension`  where   `Direct Object`='Invoice' and `Direct Object Key`=%d",$invoice_key);
		mysql_query($sql);


		$sql=sprintf("select `Category Key` from `Category Bridge`  where   `Subject`='Invoice' and `Subject Key`=%d",$invoice_key);
		$result_test_category_keys=mysql_query($sql);
		
		while ($row_test_category_keys=mysql_fetch_array($result_test_category_keys, MYSQL_ASSOC)) {
			$_category_keys[]=$row_test_category_keys['Category Key'];
		}
		$sql=sprintf("delete from `Category Bridge`  where   `Subject`='Invoice' and `Subject Key`=%d",$invoice_key);
		mysql_query($sql);

		

		$sql=sprintf("delete from `Order No Product Transaction Fact` where `Invoice Key`=%d",$invoice_key);
		mysql_query($sql);



		$sql=sprintf("delete from `Order Transaction Fact` where `Invoice Key`=%d",$invoice_key);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Dimension` where `Invoice Key`=%d",$invoice_key);
	mysql_query($sql);

}

print "finish\n";

foreach ($_category_keys as $_category_key) {
			$_category=new Category($_category_key);
			$_category->update_children_data();
			$_category->update_subjects_data();
		}
?>
