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
	}


	$sql=sprintf("update `Fork Dimension` set `Fork State`='Finished' ,`Fork Finished Date`=NOW(),`Fork Operations Done`=1,`Fork Result`='Done' where `Fork Key`=%d ",
		$fork_key
	);
	mysql_query($sql);

	return false;
}

?>
