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


	$sql=sprintf("update `Fork Dimension` set `Fork State`='In Process' ,`Fork Operations Total Operations`=1,`Fork Start Date`=NOW() where `Fork Key`=%d ",
		$fork_key
	);
	mysql_query($sql);

	switch ($fork_data['type']) {

	case 'invoice_created':
		include_once 'class.Invoice.php';
		$invoice = new invoice($fork_data['subject_key']);
		foreach ($invoice->get_delivery_notes_objects() as $key=>$dn) {
			$sql = sprintf( "insert into `Invoice Delivery Note Bridge` values (%d,%d)",  $invoice->id,$key);
			mysql_query( $sql );
			$invoice->update_xhtml_delivery_notes();
			$dn->update(array('Delivery Note Invoiced'=>'Yes'));
			$dn->update_xhtml_invoices();
		}
		foreach ($invoice->get_orders_objects() as $key=>$order) {
			$sql = sprintf( "insert into `Order Invoice Bridge` values (%d,%d)", $key, $invoice->id );
			mysql_query( $sql );
			$invoice->update_xhtml_orders();
			$order->update_xhtml_invoices();
			$order->update_no_normal_totals();
			$order->set_as_invoiced();
			$order->update_customer_history();
		}
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
			$where=sprintf(" where `Delivery Note Key`=%d",$fork_data['subject_key']);
		}else {
			$where=sprintf(" where `Inventory Transaction Key`=%d",$fork_data['subject_key']);
		}

		$sql="select  `Map To Order Transaction Fact Key`,`Inventory Transaction Key`,`Part SKU`,`Inventory Transaction Quantity`,`Date`,`Location Key` from  `Inventory Transaction Fact` ITF $where";
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$transaction_value=$dn->get_transaction_value($row['Part SKU'],-1*$row['Inventory Transaction Quantity'],$row['Date']);
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


	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=1,`Fork Result`='Done' where `Fork Key`=%d ",
		$fork_key
	);
	mysql_query($sql);

	return false;
}

?>
