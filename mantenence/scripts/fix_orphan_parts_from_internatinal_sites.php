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


//$part=new Part(37469);
//$part->update_supplied_by();
//exit;

//$product=new Product('pid',66973);

//exit;

$change=false;

$sql=sprintf("select *  from  `Product Dimension` where `Product Store Key`!=1   and `Product Code` like 'BookM-04' ");
$sql=sprintf("select *  from  `Product Dimension` where `Product Store Key`!=1   ");

$res=mysql_query($sql);
while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

	$product=new Product('pid',$row['Product ID']);
	if ($product->id) {
		$product->update_parts();
		$part_list=$product->get_all_part_skus();
		$number_parts=count($part_list);

		if ($number_parts==0) {
			print "====================shit no parts ".$product->data['Product Store Key'].' '.$product->data['Product Code']."\n";

			$_uk_product=new Product('code_store',$product->data['Product Code'],1);
			if ($_uk_product->id) {
				$_uk_parts=$_uk_product->get_current_part_skus();
				
				if (count($_uk_parts)>0) {
					$sku_from_uk=array_pop($_uk_parts);
					$part_list[]=array(
						'Product ID'=>$product->get('Product ID'),
						'Part SKU'=>$sku_from_uk,
						'Product Part Id'=>1,
						'requiered'=>'Yes',
						'Parts Per Product'=>1,
						'Product Part Type'=>'Simple Pick'
					);
					
					$product->new_current_part_list(array(),$part_list);
					//$product->update_parts();
					$__part =new Part('sku',$sku_from_uk);
					$__part->update_used_in();
				}
			}else{
							print "shit no parts and NO UK PRODUCT ".$product->data['Product Store Key'].' '.$product->data['Product Code']."\n";

			
			}



		}elseif ($number_parts==1) {
			$tmp=array_pop($part_list);
			$part=new Part($tmp);
			$products_in_part=$part->get_all_product_ids();


			$supplier_products=$part->get_all_supplier_products_pids();
			$number_supplier_products=count($supplier_products);

			$number_products_in_part=count($products_in_part);
			if ($number_products_in_part==1 and $number_supplier_products==1) {

				$supplier_product=new SupplierProduct('pid',array_pop($supplier_products));
				if (!$supplier_product->id) {
					print "errorL no supplier product ".$product->data['Product Code']."\n";

				}else {

					$uk_product=new Product('code_store',$product->data['Product Code'],1);
					if ($uk_product->id) {
						$uk_all_time_parts=$uk_product->get_all_part_skus();
						$number_uk_all_time_parts=count($uk_all_time_parts);
						if ($number_uk_all_time_parts==1) {
							$uk_part=new Part(array_pop($uk_all_time_parts));
							$uk_supplier_products=$uk_part->get_all_supplier_products_pids();
							$uk_number_supplier_products=count($uk_supplier_products);
							$uk_supplier_product=new SupplierProduct('pid',array_pop($uk_supplier_products));
							if (!$uk_supplier_product->id) {
								print 'Error no suppluer product '.$product->data['Product Code']."\n";

								continue;
							}



							if ($uk_number_supplier_products==1) {

								print $product->data['Product Store Key'].' '.$product->data['Product Code']." $number_products_in_part $number_uk_all_time_parts $uk_number_supplier_products\n";

								//print $uk_supplier_product->data['Supplier Product Valid From'].' '.$uk_supplier_product->data['Supplier Product Valid To']."\n"  ;
								//print $supplier_product->data['Supplier Product Valid From'].' '.$supplier_product->data['Supplier Product Valid To']."\n"  ;

								$sql=sprintf("select `Supplier Product Part Valid From`,`Supplier Product Part Valid To` from `Supplier Product Part Dimension`  where `Supplier Product Key`=%d   ",
									$uk_supplier_product->pid);
								$res3=mysql_query($sql);
								if ($row3=mysql_fetch_assoc($res3)) {

									if (strtotime($row3['Supplier Product Part Valid From'])<strtotime($uk_supplier_product->data['Supplier Product Valid From'])  ) {
										$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Valid From`=%s where `Supplier Product Key`=%d   ",
											prepare_mysql($row3['Supplier Product Part Valid From']),
											$uk_supplier_product->pid
										);
										//print "$sql\n";
									}
									if (strtotime($row3['Supplier Product Part Valid To'])<strtotime($uk_supplier_product->data['Supplier Product Valid To'])  ) {
										$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part To From`=%s where `Supplier Product Key`=%d   ",
											prepare_mysql($row3['Supplier Product Part Valid To']),
											$uk_supplier_product->pid
										);
										//print "$sql\n";
									}



								}


								$uk_supplier_product->update_valid_dates($supplier_product->data['Supplier Product Valid From']);
								$uk_supplier_product->update_valid_dates($supplier_product->data['Supplier Product Valid To']);



								if ($change) {
									if (strtotime($part->data['Part Valid From'])<strtotime($uk_part->data['Part Valid From'])) {
										$uk_part->update_valid_from($part->data['Part Valid From']);
									}
									if (strtotime($part->data['Part Valid To'])>strtotime($uk_part->data['Part Valid To'])) {
										$uk_part->update_valid_to($part->data['Part Valid To']);
									}
									replace_supplier_product_key($supplier_product->pid,$uk_supplier_product->pid);

									replace_sku($part->sku,$uk_part->sku);

								}

								$uk_part->update_number_transactions();
								$uk_part->update_used_in();
								$uk_part->update_supplied_by();

								$uk_part->update_picking_location();
								$uk_part->update_main_state();
								$uk_part->update_up_today_sales();
								$uk_part->update_interval_sales();
								$uk_part->update_last_period_sales();
								$product->update_web_state();
								$product->update_parts();
								$product->update_availability();

							}


						}

					}
					else {
						print $product->data['Product Code']." not uk conerpart\n";


					}


				}

			}
		}


	}

}
function replace_supplier_product_key($supplier_product_key_to_delete,$supplier_product_key) {

	if ($supplier_product_key_to_delete==$supplier_product_key) {
		print "same sup prod id\n";
		return;
	}
	$sql=sprintf("delete from `Supplier Product History Dimension` where `Supplier Product Key`=%d",$supplier_product_key_to_delete);
	mysql_query($sql);

	$sql=sprintf("delete from `Image Bridge` where `Subject`='Supplier Part' and  `Subject Key`=%d",$supplier_product_key_to_delete);
	mysql_query($sql);

	$sql=sprintf("delete from `Image Bridge` where `Subject`='Supplier Part' and  `Subject Key`=%d",$supplier_product_key_to_delete);
	mysql_query($sql);

	$sql=sprintf("delete from `Supplier Product Dimension` where `Supplier Product Key`=%d",$supplier_product_key_to_delete);
	mysql_query($sql);

	$sql=sprintf("delete from `Supplier Product History Dimension` where `Supplier Product Key`=%d",$supplier_product_key_to_delete);
	mysql_query($sql);

	$sql=sprintf("delete from `Supplier Product Part Dimension` where `Supplier Product Key`=%d",$supplier_product_key_to_delete);
	mysql_query($sql);
	//print "$sql\n";


	$sql=sprintf("update `Inventory Transaction Fact`  set `Supplier Product Key`=%d  where `Part SKU`=%d",
		$supplier_product_key,$supplier_product_key_to_delete);
	mysql_query($sql);

}


function replace_sku($sku_to_delete,$sku) {

	if ($sku_to_delete==$sku) {
		print "same sku\n";
		return;
	}
	//print "$sku_to_delete,$sku\n";

	$sql=sprintf("delete from `Inventory Spanshot Fact` where `Part SKU`=%d",$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("delete from `Part Location Dimension` where `Part SKU`=%d",$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("delete from `Part Dimension` where `Part SKU`=%d",$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("delete from `Part Warehouse Bridge` where `Part SKU`=%d",$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("delete from `Part Week Forecasting Dimension` where `Part Forecasting Part SKU`=%d",$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("delete from `Supplier Product Part List` where `Part SKU`=%d",$sku_to_delete);
	mysql_query($sql);

	$sql=sprintf("delete from `Image Bridge` where `Subject`='Part' and  `Subject Key`=%d",$sku_to_delete);
	mysql_query($sql);


	$sql=sprintf("delete from `Inventory Audit Dimension` where `Inventory Audit Part SKU`=%d",$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("update `Inventory Transaction Fact`  set `Part SKU`=%d  where `Part SKU`=%d",$sku,$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("update `List Part Bridge`  set `Part SKU`=%d  where `Part SKU`=%d",$sku,$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("update `Part Custom Field Dimension`  set `Part SKU`=%d  where `Part SKU`=%d",$sku,$sku_to_delete);
	mysql_query($sql);

	$sql=sprintf("update `Product Part List`  set `Part SKU`=%d  where `Part SKU`=%d",$sku,$sku_to_delete);
	mysql_query($sql);
	$sql=sprintf("update `Supplier Delivery Note Item Part Bridge`  set `Part SKU`=%d  where `Part SKU`=%d",$sku,$sku_to_delete);
	mysql_query($sql);

	$sql=sprintf("update `Part Picking Fact`  set `Part SKU`=%d  where `Part SKU`=%d",$sku,$sku_to_delete);
	mysql_query($sql);


}



?>
