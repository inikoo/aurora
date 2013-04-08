<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2013 Inikoo
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Country.php';
include_once '../../class.PartLocation.php';


include_once 'dropshipping_map_order_functions.php';
include_once 'common_read_orders_functions.php';
include_once 'dropshipping_common_functions.php';

error_reporting(E_ALL);

date_default_timezone_set('UTC');




$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db("dw", $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


date_default_timezone_set('UTC');
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;

include_once '../../set_locales.php';

require_once '../../conf/conf.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();


$_SESSION['lang']=1;

$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);
$store=new Store('code','DS');
$credits=array();
//$sql= "SELECT * FROM ancient_dropshipnew.`sales_flat_order`	where increment_id='AW5018'";
$sql= "SELECT * FROM ancient_dropshipnew.`sales_flat_order` where entity_id=154	";
$sql= "SELECT * FROM ancient_dropshipnew.`sales_flat_order` ";

$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {

//print "Entity: ".$row['entity_id']."\n";

//print_r($row);

	$store_code=$store->data['Store Code'];
	$order_data_id=$row['entity_id'];
	delete_old_data();
	//continue;
	if ($row['state']=='holded') {
		continue;
	}


	$sql=sprintf("select created_at from ancient_dropshipnew.sales_flat_order_status_history where parent_id=%d and status='complete'",$row['entity_id']);
	$res2=mysql_query($sql);
	//print $sql;
	if ($row2=mysql_fetch_assoc($res2)) {
		$date_inv=$row2['created_at'];
	}else {
		$date_inv=$row['created_at'];
	}


	//print $date_inv."\n";
	$customer=new Customer('old_id',$row['customer_id'],$store->id);
	if ($customer->id) {
	
		$header_data=read_header($row);
		$tax_category_object=get_tax_code($store->data['Store Code'],$header_data);

		$customer_service_rep_data=array('id'=>0);
		$customer_key=$customer->id;
		$filename='';

		$date_order=$row['created_at'];
		$shipping_net=$header_data['shipping'];
		$charges_net=0;

		$data_dn_transactions=array();
		$discounts_with_order_as_term=array();



		//
		
		//print "C:".$customer->id."\n";
		$sql=sprintf("select * from ancient_dropshipnew.`sales_flat_order_item` WHERE `order_id`=%d ",
			$row['entity_id']
		);
		$res2=mysql_query($sql);
		while ($row2=mysql_fetch_assoc($res2)) {
			$department=new Department('code_store','ND_'.$store->data['Store Code'],$store->id);
			$family=new Family('code_store','PND_'.$store->data['Store Code'],$store->id);
			$w=$row2['weight'];
			$product_data=array(
				'Product Store Key'=>$store->id,
				'Product Main Department Key'=>$department->id,
				'Product Sales Type'=>'Not for Sale',
				'Product Type'=>'Normal',
				'Product Record Type'=>'Normal',
				'Product Web Configuration'=>'Offline',

				'Product Family Key'=>$family->id,
				'Product Locale'=>'en_GB',
				'Product Currency'=>$row['base_currency_code'],
				'Product code'=>$row2['sku'],
				'Product name'=>$row2['name'],
				'Product unit type'=>'Piece',
				'Product units per case'=>1,
				'Product net weight'=>$w,
				'Product gross weight'=>$w,
				'part gross weight'=>$w,
				'Product rrp'=>'',
				'product price'=>sprintf("%.2f",$row2['original_price']),
				'product valid from'=>$row['created_at'],
				'product valid to'=>$row['created_at'],
				'editor'=>array('Date'=>$row['created_at']
				)
			);

			//      print_r( $product_data);

			$product=new Product('find',$product_data,'create');
			
			
			$parts= $product->get_all_part_skus();
			
			if(count($parts)==0){
				print $product->data['Product Code']."\n";
				continue;
			}
			
			foreach ($parts as $part_sku) {
				$part=new Part($part_sku);
				$part->update_valid_dates($date_order);
				$part->update_valid_dates($date_inv);
			}

			$sql=sprintf("select PPD.`Product Part Key` ,`Parts Per Product` from  `Product Part Dimension`  PPD  left join  `Product Part List` PPL on (PPL.`Product Part Key`=PPD.`Product Part Key`)where `Product ID`=%d and `Part SKU`=%d    ",
				$product->pid,
				$part->sku

			);
			//print $sql;
			$res3=mysql_query($sql);
			if ($row3=mysql_fetch_assoc($res3)) {
				$parts_per_product=$row3['Parts Per Product'];
			}else{
				$parts_per_product=1;
			}

			$part_list=array();
			$part_list[]=array(

				'Part SKU'=>$part->sku,

				'Parts Per Product'=>$parts_per_product,
				'Product Part Type'=>'Simple'

			);
			//print "xxxx\n";
			//print_r($part_list);
			$product_part_key=$product->find_product_part_list($part_list);
			
			//print "xx $product_part_key xx\n";
			if (!$product_part_key) {
				//print_r($product);
				//print_r($part_list);
				exit("Error can not find product part list \n");
			}

			$product->update_product_part_list_historic_dates($product_part_key,$date_order,$date_inv);

			//$part_list=$product->get_part_list($date_order);
			//if(count($part_list)==0){
			//$part->update_valid_dates($date_order);
			// $part->update_valid_dates($date2);
			//}

			//print_r($product_data);
			//print "New PK ".$product->new_key."\n";
			//print "New PID ".$product->new_id."\n";
			//print "PID ".$product->pid."\n";
			$qty=$row2['qty_ordered'];
			$price=$row2['original_price'];
			$transaction=array(
				'Product Key' => $product->id,
				'Estimated Weight' => $w*$qty,
				'qty' => $qty,
				'gross_amount' =>$qty*$price,
				'discount_amount' =>$qty*$row2['price'],
				'units_per_case' => 1,
				'code'=>$product->data['Product Code'],
				'description' => $row2['name'],
				'price' => $price,
				'order' => $qty,
				'reorder' => 0,
				'bonus' => 0,
				'credit' => 0,
				'rrp' => '',
				'discount' => '',
				'units' => 1,
				'supplier_code' => '',
				'supplier_product_code' => '',
				'supplier_product_cost' => '',
				'w' => $w,
				'name' => $row2['name'],
				'fob' => '',
				'original_price' => $price




			);


			$used_parts_sku=false;




			create_dn_invoice_transactions($transaction,$product,$used_parts_sku);

		}

		list($address1,$address2,$town,$postcode,$country_div,$country)=get_address($row['shipping_address_id']);

		$country=new Country('find',$country);
		$shipping_addresses['Ship To Line 1']=$address1;
		$shipping_addresses['Ship To Line 2']=$address2;
		$shipping_addresses['Ship To Line 3']='';
		$shipping_addresses['Ship To Town']=$town;
		$shipping_addresses['Ship To Postal Code']=$postcode;
		$shipping_addresses['Ship To Country Code']=$country->data['Country Code'];
		$shipping_addresses['Ship To Country Name']=$country->data['Country Name'];
		$shipping_addresses['Ship To Country Key']=$country->id;
		$shipping_addresses['Ship To Country 2 Alpha Code']=$country->data['Country 2 Alpha Code'];
		$shipping_addresses['Ship To Country First Division']=$country_div;
		$shipping_addresses['Ship To Country Second Division']='';

		$ship_to= new Ship_To('find create',$shipping_addresses);

		if ($ship_to->id) {
			//print_r($ship_to);
			$customer->associate_ship_to_key($ship_to->id,$row['created_at'],false);

		}else {
			exit("error with the shipping address\n");
		}

		$data=array();
		$editor['Date']=$row['created_at'];
		$data['editor']=$editor;
		$data['order_date']=$row['created_at'];
		$data['order id']=$row['increment_id'];
		$data['order customer message']=$row['customer_note'];
		$data['order original data source']='Magento';
		$data['Order For']='Customer';
		$data['Order Main Source Type']='Internet';
		$data['Delivery Note Dispatch Method']='Shipped';
		$data['staff sale']='no';
		$data['staff sale key']=0;
		$data['Order Ship To Key']=$ship_to->id;
		$data['Order Customer Key']=$customer->id;

		$data['Order Type']='Order';

		//print_r($data_dn_transactions);
	//	print_r($data);


		create_order($data);



		if ($row['state']=='complete' or $row['state']=='closed') {
			send_order($data,$data_dn_transactions);

		}elseif ($row['state']=='canceled') {
			$order->cancel('',$date_order);
		}
		
		
		$sql=sprintf("INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Import Date`) VALUES (%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Import Date`=%s",
				prepare_mysql($store_code.$order_data_id),
				prepare_mysql($row['increment_id']),
				prepare_mysql($row['updated_at']),
				prepare_mysql($row['increment_id']),
				prepare_mysql($row['updated_at'])
			);

			mysql_query($sql);
		

	}
}
?>
