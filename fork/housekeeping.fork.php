<?php
/*
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 15 November 2014 11:35:49 GMT, Langley Mill Uk
 Copyright (c) 2014, Inikoo

 Version 2.0
*/



function fork_housekeeping($job) {


	if (!$_data=get_fork_data($job))
		return;


	

	$fork_data=$_data['fork_data'];
	$fork_key=$_data['fork_key'];






	switch ($fork_data['type']) {

	case 'update_part_products_availability':

		include_once 'class.Part.php';

		$part=new Part($fork_data['part_sku']);

		foreach ($part->get_products('objects') as $product) {
			$product->update_availability($use_fork=false);
		}

		break;
		
	case 'update_web_state_slow_forks':

		include_once 'class.Product.php';

		$product=new Product('id',$fork_data['product_id']);

		$product->update_web_state_slow_forks($fork_data['web_availability_updated']);
		

		break;	
		
	case 'update_otf':
		include_once 'class.Order.php';

		$order = new Order($fork_data['order_key']);
		$order->update_deals_usage();
		break;
	case 'invoice_created':
		include_once 'class.Invoice.php';
		$invoice = new invoice($fork_data['subject_key']);

		$invoice->update_xhtml_sale_representatives();
		$invoice->categorize();

		break;
	case 'order_created':
		include_once 'class.Order.php';
		include_once 'class.Customer.php';
		include_once 'class.Store.php';
		$order = new Order($fork_data['subject_key']);

		$customer=new Customer($order->data['Order Customer Key']);
		$customer->editor=$fork_data['editor'];
		$customer->add_history_new_order($order);
		$customer->update_orders();
		$customer->update_no_normal_data();
		$store=new Store($order->data['Order Store Key']);
		$store->update_orders();
		$order->update_full_search();


		break;

	case 'delivery_note_picked':
	case 'item_picked':
		include_once 'class.DeliveryNote.php';
		include_once 'class.PartLocation.php';

		$dn= new DeliveryNote($fork_data['delivery_note_key']);

		if ($fork_data['type']=='delivery_note_picked') {
			$where=sprintf(" where `Delivery Note Key`=%d", $fork_data['subject_key']);
		}else {
			$where=sprintf(" where `Inventory Transaction Key`=%d", $fork_data['subject_key']);
		}

		$sql="select  `Map To Order Transaction Fact Key`,`Inventory Transaction Key`,`Part SKU`,`Inventory Transaction Quantity`,`Date`,`Location Key` from  `Inventory Transaction Fact` ITF $where";
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$transaction_value=$dn->get_transaction_value($row['Part SKU'], -1*$row['Inventory Transaction Quantity'], $row['Date']);
			$cost_storing=0;//to do

			$sql = sprintf("update `Inventory Transaction Fact` set `Inventory Transaction Amount`=%f where `Inventory Transaction Key`=%d  ",
				$transaction_value,
				$row['Inventory Transaction Key']
			);
			mysql_query($sql);

			$sql = sprintf("update `Order Transaction Fact` set `Cost Supplier`=%f,`Cost Storing`=%f where `Order Transaction Fact Key`=%d  ",

				$transaction_value,
				$cost_storing,
				$row['Map To Order Transaction Fact Key']
			);
			mysql_query($sql);

			$part_location=new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
			$part_location->update_stock();

		}
		break;

	case 'send_to_warehouse':

		include_once 'class.PartLocation.php';

		$sql=sprintf("select `Part SKU`,`Location Key` from  `Inventory Transaction Fact` ITF where `Delivery Note Key`=%d",
			$fork_data['delivery_note_key']);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$part_location=new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
			$part_location->update_stock();


		}
		break;

	}




	return false;
}


?>
