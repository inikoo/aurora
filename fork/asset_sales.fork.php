<?php
/*
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 5 October 2016 at 12:12:33 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2014, Inikoo

 Version 2.0
*/



function fork_asset_sales($job) {


	if (!$_data=get_fork_metadata($job))
		return;


	list($account, $db, $data)=$_data;

	//print_r($data);

	switch ($data['type']) {

	case 'update_products_sales_data':
		include_once 'class.Product.php';

		$sql=sprintf("select `Product ID` from `Product Dimension`  ");
		if ($result=$db->query($sql)) {
			foreach ($result as $row) {

				$product=new Product('id', $row['Product ID']);
				$product->load_acc_data();
				$product->update_sales_from_invoices($data['interval']);
			}
		}
		break;

	case 'update_parts_sales_data':
		include_once 'class.Part.php';

		$sql=sprintf("select `Part SKU` from `Part Dimension`  ");
		if ($result=$db->query($sql)) {
			foreach ($result as $row) {

				$part=new Part($row['Part SKU']);
				$part->load_acc_data();
				$part->update_sales_from_invoices($data['interval']);
			}
		}
		break;
	case 'update_product_categories_sales_data':
		include_once 'class.Category.php';

		$sql=sprintf("select `Category Key` from `Category Dimension` where   `Category Scope`='Product' ");

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {
				$category=new Category($row['Category Key']);
				$category->update_part_category_sales($data['interval']);
			}
		}
		break;
	case 'update_part_categories_sales_data':
		include_once 'class.Category.php';

		$sql=sprintf("select `Category Key` from `Category Dimension` where   `Category Scope`='Part' ");

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {
				$category=new Category($row['Category Key']);
				$category->update_part_category_sales($data['interval']);
			}
		}
		break;	
		

	case 'update_delivery_note_part_sales_data':

		include_once 'class.Part.php';
		include_once 'class.Customer.php';
		include_once 'class.Category.php';

		$categories=array();
		//print_r($data);

		$customer=new Customer($data['customer_key']);
		$customer->update_part_bridge();

		$sql=sprintf("select `Part SKU` from `Inventory Transaction Fact` where `Delivery Note Key`=%d", $data['delivery_note_key']);

		//print "$sql\n";

		if ($result=$db->query($sql)) {
			foreach ($result as $row) {
				$part=new Part($row['Part SKU']);


				//print $part->get('Reference')."\n";

				$part->load_acc_data();

				$part->update_sales_from_invoices('Total');
				$part->update_sales_from_invoices('Week To Day');
				$part->update_sales_from_invoices('Month To Day');
				$part->update_sales_from_invoices('Quarter To Day');
				$part->update_sales_from_invoices('Year To Day');
				$part->update_sales_from_invoices('1 Year');
				$part->update_sales_from_invoices('1 Quarter');
				$part->update_sales_from_invoices('1 Month');
				$part->update_sales_from_invoices('Today');

				$categories=$categories+$part->get_categories();


			}
		}

		foreach ($categories as $category_key) {

			$category=new Category($category_key);


			// print $category->get('Code')."\n";

			$category->update_part_category_sales('Total');
			$category->update_part_category_sales('Week To Day');
			$category->update_part_category_sales('Month To Day');
			$category->update_part_category_sales('Quarter To Day');
			$category->update_part_category_sales('Year To Day');
			$category->update_part_category_sales('1 Year');
			$category->update_part_category_sales('1 Quarter');
			$category->update_part_category_sales('1 Month');
			$category->update_part_category_sales('Today');

		}


		break;

	case 'update_invoice_products_sales_data':
		update_invoice_products_sales_data($db, $data);

		break;
	case 'update_deleted_invoice_products_sales_data':
		update_deleted_invoice_products_sales_data($db, $data);





		break;

	}




	return false;
}


function update_invoice_products_sales_data($db, $data) {

	include_once 'class.Product.php';
	include_once 'class.Customer.php';
	include_once 'class.Category.php';
	include_once 'class.Store.php';
	include_once 'class.Invoice.php';




	$categories=array();
	$categories_bis=array();
	//print_r($data);

	$customer=new Customer($data['customer_key']);
	$customer->update_product_bridge();


	$store=new Store($data['store_key']);

	$store->update_sales_from_invoices('Total');
	$store->update_sales_from_invoices('Week To Day');
	$store->update_sales_from_invoices('Month To Day');

	$store->update_sales_from_invoices('Quarter To Day');
	$store->update_sales_from_invoices('Year To Day');

	$store->update_sales_from_invoices('1 Year');
	$store->update_sales_from_invoices('1 Quarter');
	$store->update_sales_from_invoices('1 Month');
	$store->update_sales_from_invoices('1 Week');
	$store->update_sales_from_invoices('Today');


	$invoice=new Invoice( $data['invoice_key']);
	$invoice->categorize();

	$sql=sprintf("select `Product ID`,`Invoice Date` from `Order Transaction Fact` where `Invoice Key`=%d", $data['invoice_key']);



	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$product=new Product('id', $row['Product ID']);








			//print $product->get('Code')."\n";

			$product->load_acc_data();

			$product->update_sales_from_invoices('Total');
			$product->update_sales_from_invoices('Week To Day');
			$product->update_sales_from_invoices('Month To Day');
			$product->update_sales_from_invoices('Quarter To Day');
			$product->update_sales_from_invoices('Year To Day');
			$product->update_sales_from_invoices('1 Year');
			$product->update_sales_from_invoices('1 Quarter');
			$product->update_sales_from_invoices('1 Month');
			$product->update_sales_from_invoices('1 Week');

			$product->update_sales_from_invoices('Today');

			$categories=$categories+$product->get_categories();


		}
	}




	foreach ($categories as $category_key) {

		$category=new Category($category_key);


		//print $category->get('Code')."\n";

		$category->update_product_category_sales('Total');
		$category->update_product_category_sales('Week To Day');
		$category->update_product_category_sales('Month To Day');
		$category->update_product_category_sales('Quarter To Day');
		$category->update_product_category_sales('Year To Day');
		$category->update_product_category_sales('1 Year');
		$category->update_product_category_sales('1 Quarter');
		$category->update_product_category_sales('1 Month');
		$category->update_product_category_sales('1 Week');
		$category->update_product_category_sales('Today');

		$categories_bis=$categories_bis+$category->get_categories();


	}

	foreach ($categories_bis as $category_key) {

		$category=new Category($category_key);


		//print $category->get('Code')."\n";

		$category->update_product_category_sales('Total');
		$category->update_product_category_sales('Week To Day');
		$category->update_product_category_sales('Month To Day');
		$category->update_product_category_sales('Quarter To Day');
		$category->update_product_category_sales('Year To Day');

		$category->update_product_category_sales('1 Year');
		$category->update_product_category_sales('1 Quarter');
		$category->update_product_category_sales('1 Month');
		$category->update_product_category_sales('1 Week');
		$category->update_product_category_sales('Today');



	}



}


function update_deleted_invoice_products_sales_data($db, $data) {

	include_once 'class.Product.php';
	include_once 'class.Customer.php';
	include_once 'class.Category.php';
	include_once 'class.Store.php';
	include_once 'class.Invoice.php';




	$categories=array();
	$categories_bis=array();
	//print_r($data);

	$customer=new Customer($data['customer_key']);
	$customer->update_product_bridge();


	$store=new Store($data['store_key']);

	$store->update_sales_from_invoices('Total');
	$store->update_sales_from_invoices('Week To Day');
	$store->update_sales_from_invoices('Month To Day');

	$store->update_sales_from_invoices('Quarter To Day');
	$store->update_sales_from_invoices('Year To Day');

	$store->update_sales_from_invoices('1 Year');
	$store->update_sales_from_invoices('1 Quarter');
	$store->update_sales_from_invoices('1 Month');
	$store->update_sales_from_invoices('1 Week');
	$store->update_sales_from_invoices('Today');


	//todo don't calculate the ones not applicable
	$store->update_sales_from_invoices('Yesterday');
	$store->update_sales_from_invoices('Last Week');
	$store->update_sales_from_invoices('Last Month');
	//------




	foreach ($data['products'] as $product_id=>$tmp) {
		$product=new Product('id', $product_id);


		$product->load_acc_data();

		$product->update_sales_from_invoices('Total');
		$product->update_sales_from_invoices('Week To Day');
		$product->update_sales_from_invoices('Month To Day');
		$product->update_sales_from_invoices('Quarter To Day');
		$product->update_sales_from_invoices('Year To Day');
		$product->update_sales_from_invoices('1 Year');
		$product->update_sales_from_invoices('1 Quarter');
		$product->update_sales_from_invoices('1 Month');
		$product->update_sales_from_invoices('1 Week');
		$product->update_sales_from_invoices('Today');

		//todo don't calculate the ones not applicable
		$product->update_sales_from_invoices('Yesterday');
		$product->update_sales_from_invoices('Last Week');
		$product->update_sales_from_invoices('Last Month');
		$product->update_previous_quarters_data();
		$product->update_previous_years_data();
		//------



		$categories=$categories+$product->get_categories();


	}



	foreach ($categories as $category_key) {

		$category=new Category($category_key);


		//print $category->get('Code')."\n";

		$category->update_product_category_sales('Total');
		$category->update_product_category_sales('Week To Day');
		$category->update_product_category_sales('Month To Day');
		$category->update_product_category_sales('Quarter To Day');
		$category->update_product_category_sales('Year To Day');
		$category->update_product_category_sales('1 Year');
		$category->update_product_category_sales('1 Quarter');
		$category->update_product_category_sales('1 Month');
		$category->update_product_category_sales('1 Week');
		$category->update_product_category_sales('Today');

		//todo don't calculate the ones not applicable
		$category->update_product_category_sales('Yesterday');
		$category->update_product_category_sales('Last Week');
		$category->update_product_category_sales('Last Month');
		$category->update_product_category_previous_quarters_data();
		$category->update_product_category_previous_years_data();
		//------

		$categories_bis=$categories_bis+$category->get_categories();


	}

	foreach ($categories_bis as $category_key) {

		$category=new Category($category_key);


		//print $category->get('Code')."\n";

		$category->update_product_category_sales('Total');
		$category->update_product_category_sales('Week To Day');
		$category->update_product_category_sales('Month To Day');
		$category->update_product_category_sales('Quarter To Day');
		$category->update_product_category_sales('Year To Day');

		$category->update_product_category_sales('1 Year');
		$category->update_product_category_sales('1 Quarter');
		$category->update_product_category_sales('1 Month');
		$category->update_product_category_sales('1 Week');
		$category->update_product_category_sales('Today');

		//todo don't calculate the ones not applicable
		$category->update_product_category_sales('Yesterday');
		$category->update_product_category_sales('Last Week');
		$category->update_product_category_sales('Last Month');
		$category->update_product_category_previous_quarters_data();
		$category->update_product_category_previous_years_data();
		//------

	}



}


?>
