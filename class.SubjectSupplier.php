<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 May 2016 at 10:56:11 GMT+7, Bandung, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.Subject.php';


class SubjectSupplier extends Subject {

	function create_order($_data) {

		include_once 'class.Staff.php';
		include_once 'class.Warehouse.php';

		$staff=new Staff($_data['user']->get('User Parent Key'));
		$warehouse=new Warehouse($_data['warehouse_key']);

		$order_data=array(
			'Purchase Order Parent'=>$this->table_name,
			'Purchase Order Parent Key'=>$this->id,
			'Purchase Order Parent Name'=>$this->get('Name'),
			'Purchase Order Parent Code'=>$this->get('Code'),
			'Purchase Order Parent Contact Name'=>$this->get('Main Contact Name'),
			'Purchase Order Parent Email'=>$this->get('Main Plain Email'),
			'Purchase Order Parent Telephone'=>$this->get('Preferred Contact Number Formatted Number'),
			'Purchase Order Parent Address'=>$this->get('Contact Address Formatted'),

			'Purchase Order Currency Code'=>$this->get('Default Currency Code'),
			'Purchase Order Incoterm'=>$this->get('Default Incoterm'),
			'Purchase Order Port of Import'=>$this->get('Default Port of Import'),
			'Purchase Order Port of Export'=>$this->get('Default Port of Export'),


			'Purchase Order Warehouse Key'=>$warehouse->data['Warehouse Key'],
			'Purchase Order Warehouse Code'=>$warehouse->data['Warehouse Code'],
			'Purchase Order Warehouse Name'=>$warehouse->data['Warehouse Name'],
			'Purchase Order Warehouse Address'=>$warehouse->data['Warehouse Address'],
			'Purchase Order Warehouse Company Name'=>$warehouse->data['Warehouse Company Name'],
			'Purchase Order Warehouse Company Number'=>$warehouse->data['Warehouse Company Number'],
			'Purchase Order Warehouse VAT Number'=>$warehouse->data['Warehouse VAT Number'],
			'Purchase Order Warehouse Telephone'=>$warehouse->data['Warehouse Telephone'],
			'Purchase Order Warehouse Email'=>$warehouse->data['Warehouse Email'],

			'Purchase Order Terms and Conditions'=>$this->get('Default PO Terms and Conditions'),
			'Purchase Order Main Buyer Key'=>$staff->id,
			'Purchase Order Main Buyer Name'=>$staff->get('Staff Name'),
			'editor'=>$this->editor
		);





		if ($this->get('Show Warehouse TC in PO')=='Yes') {

			if ($order_data['Purchase Order Terms and Conditions']!='') {
				$order_data['Purchase Order Terms and Conditions'].='<br><br>';
			}
			$order_data['Purchase Order Terms and Conditions'].=$warehouse->data['Warehouse Default PO Terms and Conditions'];
		}








		$order=new PurchaseOrder('new', $order_data);



		if ($order->error) {
			$this->error=true;
			$this->msg=$order->msg;
			return $order;
		}




		return $order;

	}


	function update_orders() {
		$number_purchase_orders=0;
		$number_open_purchase_orders=0;
		$number_delivery_notes=0;
		$number_invoices=0;

		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Parent`=%s and `Purchase Order Parent Key`=%d",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_purchase_orders=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Parent`=%s and  `Purchase Order Parent Key`=%d and `Purchase Order State` not in ('Done','Cancelled')",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_open_purchase_orders=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select count(*) as num from `Purchase Delivery Note Dimension` where `Purchase Delivery Note Parent`=%s and  `Purchase Delivery Note Parent Key`=%d",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_delivery_notes=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("select count(*) as num from `Purchase Invoice Dimension` where `Purchase Invoice Parent`=%s and  `Purchase Invoice Parent Key`=%d",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_invoices=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("update `%s Dimension` set `%s Purchase Orders`=%d,`%s Open Purchase Orders`=%d ,`%s Purchase Delivery Notes`=%d ,`%s Purchase Invoices`=%d where `%s Key`=%d",
			$this->table_name,
			$this->table_name,
			$number_purchase_orders,
			$this->table_name,
			$number_open_purchase_orders,
			$this->table_name,
			$number_delivery_notes,
			$this->table_name,
			$number_invoices,
			$this->table_name,
			$this->id);
		$this->db->exec($sql);

	}


}


?>
