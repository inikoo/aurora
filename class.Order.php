<?php
/*
  File: Ordes.php

  This file contains the Order Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';

include_once 'class.Staff.php';
include_once 'class.Supplier.php';
include_once 'class.Customer.php';
include_once 'class.Store.php';
include_once 'class.Ship_To.php';
include_once 'class.Invoice.php';

include_once 'class.DeliveryNote.php';
include_once 'class.TaxCategory.php';
include_once 'class.CurrencyExchange.php';


class Order extends DB_Table {
	//Public $data = array ();
	// Public $items = array ();
	// Public $status_names = array ();
	// Public $id = false;
	// Public $tipo;
	// Public $staus = 'new';


	var $ghost_order=false;
	var $update_stock=true;	
	public $skip_update_product_sales=false;
	var $skip_update_after_individual_transaction=true;
	function __construct($arg1 = false, $arg2 = false) {

		$this->table_name='Order';
		$this->ignore_fields=array('Order Key');
		$this->update_customer=true;

		$this->status_names = array (0 => 'new' );
		if (preg_match( '/new/i', $arg1 )) {
			$this->create_order ( $arg2 );
			return;
		}
		if (is_numeric( $arg1 )) {
			$this->get_data ( 'id', $arg1 );
			return;
		}
		$this->get_data ( $arg1, $arg2 );

	}

	/*   function set_adjust_amounts($tipo,$amount){ */
	/*     $this->adjusts[$tipo]=$amount; */
	/*   } */
	/*   function get_adjust_amounts($tipo,$amount){ */
	/*     if(array_key_exists($tipo,$this->adjusts)) */
	/*       return $this->adjusts[$tipo]; */
	/*     else */
	/*       false; */
	/*   } */


	function create_refund($data=false) {
		$refund_tag='ref';
		$refund_data=array(
			'Invoice Customer Key'=>$this->data['Order Customer Key'],
			'Invoice Store Key'=>$this->data['Order Store Key'],
			'Order Key'=>$this->id,

			'Invoice Public ID'=>$this->data['Order Public ID'].$refund_tag
		);
		if (!$data)$data=array();

		if (array_key_exists('Invoice Metadata',$data))$refund_data['Invoice Metadata']=$data['Invoice Metadata'];
		if (array_key_exists('Invoice Date',$data))$refund_data['Invoice Date']=$data['Invoice Date'];
		if (array_key_exists('Invoice Tax Code',$data))$refund_data['Invoice Tax Code']=$data['Invoice Tax Code'];

		$refund=new Invoice('create refund',$refund_data);


		return $refund;
	}

	function create_order($data) {



		global $myconf;


		if (isset($data['editor'])) {
			foreach ($data['editor'] as $key=>$value) {
				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}
		$this->editor=$data ['editor'];


		$this->data ['Order Type'] = $data ['Order Type'];
		if (isset($data['Order Date']))
			$this->data ['Order Date'] =$data['Order Date'];
		else
			$this->data ['Order Date'] = date('Y-m-d H:i:s');

		//   if(isset($data['Order Ship To Key'])){

		//   $this->ship_to=new Ship_To($data['Order Ship To Key']);
		// }

		//print_r($this->ship_to);
		if (isset($data['Order Tax Code'])) {

			$tax_cat=new TaxCategory('code',$data['Order Tax Code']);
			if ($tax_cat->id) {
				$this->data ['Order Tax Code']=$tax_cat->data['Tax Category Code'];
				$this->data['Order Tax Rate']=$tax_cat->data['Tax Category Rate'];
			}
		}

		$this->set_data_from_customer($data['Customer Key']);

		//print_r($data);
		//exit;

		// $this->data ['Order Ship To Key To Deliver']=$this->ship_to->id;
		//$this->data ['Destination Country 2 Alpha Code']=($this->ship_to->data['Ship To Country 2 Alpha Code']==''?'XX':$this->ship_to->data['Ship To Country 2 Alpha Code']);
		//$this->data ['Order XHTML Ship Tos']=$this->ship_to->data['Ship To XHTML Address'];
		//$this->data ['Order Ship To Keys']=$this->ship_to->id;
		//$this->data ['Order Ship To Country Code']=($this->ship_to->data['Ship To Country Code']==''?'UNK':$this->ship_to->data['Ship To Country Code']);




		$this->data ['Order Current Dispatch State'] = 'In Process';
		$this->data ['Order Current Payment State'] = 'Waiting Payment';
		$this->data ['Order Current XHTML State'] = 'In Process';
		$this->data ['Order Sale Reps IDs'] =array($this->editor['User Key']);
		$this->data ['Order For'] = 'Customer';

		$this->data ['Order Customer Message']='';


		if (isset($data['Order Original Data MIME Type']))
			$this->data ['Order Original Data MIME Type']=$data['Order Original Data MIME Type'];
		else
			$this->data ['Order Original Data MIME Type']='none';




		if (isset($data['Order Original Metadata']))
			$this->data ['Order Original Metadata']=$data['Order Original Metadata'];
		else
			$this->data ['Order Original Metadata']='';


		if (isset($data['Order Original Data Source']))
			$this->data ['Order Original Data Source']=$data['Order Original Data Source'];
		else
			$this->data ['Order Original Data Source']='Other';


		if (isset($data['Order Original Data Filename']))
			$this->data ['Order Original Data Filename']=$data['Order Original Data Filename'];
		else
			$this->data ['Order Original Data Filename']='Other';



		$this->data ['Order Currency Exchange']=1;
		$sql=sprintf("select `HQ Currency` from `HQ Dimension`");
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$corporation_currency_code=$row['HQ Currency'];
		} else
			$corporation_currency_code='GBP';
		if ($this->data ['Order Currency']!=$corporation_currency_code) {
			$currency_exchange = new CurrencyExchange($this->data ['Order Currency'].$corporation_currency_code,$this->data['Order Date']);
			$exchange= $currency_exchange->get_exchange();
			$this->data ['Order Currency Exchange']=$exchange;
		}

		$this->data ['Order Main Source Type']='Call';
		if (isset($data['Order Main Source Type']) and preg_match('/^(Internet|Call|Store|Unknown|Email|Fax)$/i'))
			$this->data ['Order Main Source Type']=$data['Order Main Source Type'];

		if (isset($data ['Order Public ID'])) {
			$this->data ['Order Public ID'] = $data ['Order Public ID'];
			$this->data ['Order File As'] = $this->prepare_file_as($data ['Order Public ID']);
		} else {
			$this->next_public_id();
		}



		$this->create_order_header ();
		foreach ( $this->data ['Order Sale Reps IDs'] as $sale_rep_id ) {
			$sql = sprintf( "insert into `Order Sales Rep Bridge`  (%d,%d)", $this->id, $sale_rep_id );
		}
		$this->get_data('id',$this->id);
		$this->update_charges();
		if ($this->data['Order Shipping Method']=='Calculated') {
			$this->update_shipping();

		}

		$customer=new Customer($data['Customer Key']);
		$customer->editor=$this->editor;

		$customer->add_history_new_order($this);

		$customer->update_orders();
		$customer->update_no_normal_data();



		$this->update_full_search();
		if (!$this->ghost_order) {
			$this->get_data('id',$this->id);
			$this->update_item_totals_from_order_transactions();
			$this->update_totals_from_order_transactions();
		}
	}

	function send_to_warehouse($date=false,$extra_data=false) {


		if (!$date)
			$date=date('Y-m-d H:i:s');

		if (!($this->data['Order Current Dispatch State']=='In Process' or $this->data['Order Current Dispatch State']=='Submited')) {
			$this->error=true;
			$this->msg='Order is not in process';
			return;

		}

		if ($this->data['Order For Collection']=='Yes') {
			$dispatch_method='Collection';
		} else {
			$dispatch_method='Dispatch';
		}
		$data_dn=array(
			'Delivery Note Date Created'=>$date,
			'Delivery Note ID'=>$this->data['Order Public ID'],
			'Delivery Note File As'=>$this->data['Order File As'],
			'Delivery Note Type'=>$this->data['Order Type'],
			'Delivery Note Dispatch Method'=>$dispatch_method,
			'Delivery Note Title'=>_('Delivery Note for').' '.$this->data['Order Type'].' <a href="order.php?id='.$this->id.'">'.$this->data['Order Public ID'].'</a>',
			'Delivery Note Customer Key'=>$this->data['Order Customer Key'],
			'Delivery Note Metadata'=>$this->data['Order Original Metadata']

		);


		$dn=new DeliveryNote('create',$data_dn,$this);
		$dn->update_stock=$this->update_stock;
		$dn->create_inventory_transaction_fact($this->id,$extra_data);
		$this->update_delivery_notes('save');
		$this->data['Order Current Dispatch State']='Ready to Pick';
		$this->data['Order Current XHTML Dispatch State']=_('Ready to Pick');
		$this->data['Order Current XHTML State']=$this->calculate_state();
		$sql=sprintf("update `Order Dimension` set `Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML State`=%s  where `Order Key`=%d"
			,prepare_mysql($this->data['Order Current Dispatch State'])
			,prepare_mysql($this->data['Order Current XHTML Dispatch State'])
			,prepare_mysql($this->data['Order Current XHTML State'])
			,$this->id
		);

		mysql_query($sql);
		$this->update_full_search();

		return $dn;
	}




	function send_post_action_to_warehouse($date=false,$type=false,$metadata='') {
		if (!$date)
			$date=date('Y-m-d H:i:s');

		if (!$this->data['Order Current Dispatch State']=='Dispatched') {
			$this->error=true;
			$this->msg='Order is not already dispatched';
			return;

		}
		if (!$type) {
			$type='Replacement & Shortages';
		}


		$type_formated=$type;
		$title="Delivery Note for $type of ".$this->data['Order Type'].' <a href="order.php?id='.$this->id.'">'.$this->data['Order Public ID'].'</a>';

		if ($this->data['Order For Collection']=='Yes')
			$dispatch_method='Collection';
		else
			$dispatch_method='Dispatch';
		if ($type=='Replacement')
			$suffix='rpl';
		elseif ($type=='Missing')
			$suffix='sh';
		else
			$suffix='';
		$data_dn=array(
			'Delivery Note Date Created'=>$date,
			'Delivery Note ID'=>$this->data['Order Public ID']."$suffix",
			'Delivery Note File As'=>$this->data['Order File As']."$suffix",
			'Delivery Note Type'=>$type,
			'Delivery Note Title'=>$title,
			'Delivery Note Dispatch Method'=>$dispatch_method,
			'Delivery Note Metadata'=>$metadata,
			'Delivery Note Customer Key'=>$this->data['Order Customer Key']

		);






		$dn=new DeliveryNote('create',$data_dn,$this);
		$dn->create_post_order_inventory_transaction_fact($this->id);
		$this->update_delivery_notes('save');
		//TODO!!!
		$this->update_dispatch_state();

		$this->update_full_search();

		$customer=new Customer($this->data['Order Customer Key']);
		$customer->add_history_post_order_in_warehouse($dn,$type);
		return $dn;
	}

	function cancel($note='',$date=false) {

		$this->cancelled=false;
		if (preg_match('/Dispatched/',$this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order can not be cancelled, because has already been dispatched');

		}
		if (preg_match('/Cancelled/',$this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order is already cancelled');

		} else {

			if (!$date)
				$date=date('Y-m-d H:i:s');
			$this->data ['Order Cancelled Date'] = $date;

			$this->data ['Order Cancel Note'] = $note;

			$this->data ['Order Current Payment State'] = 'Cancelled';
			$this->data ['Order Current Dispatch State'] = 'Cancelled';
			$this->data ['Order Current XHTML Dispatch State'] = _('Cancelled');
			$this->data ['Order Current XHTML State'] = _ ( 'Order Cancelled' );
			$this->data ['Order XHTML Invoices'] = '';
			$this->data ['Order XHTML Delivery Notes'] = '';
			$this->data ['Order Balance Total Amount'] = 0;
			$this->data ['Order Balance Net Amount'] = 0;
			$this->data ['Order Balance Tax Amount'] = 0;
			$this->data ['Order Outstanding Balance Total Amount'] = 0;
			$this->data ['Order Outstanding Balance Net Amount'] = 0;
			$this->data ['Order Outstanding Balance Tax Amount'] = 0;


			//$no_shipped=0;

			//$no_shipped=$this->data['Order Quantity']-$this->data['No Shipped Due Out of Stock']-$this->data['No Shipped Due Not Found']-$this->data['No Shipped Due No Authorized'];
			//if($no_shipped<0)$no_shipped=0;
			// `No Shipped Due Other`=%d,

			$sql = sprintf( "update `Order Dimension` set    `Order Cancelled Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML State`=%s,`Order XHTML Invoices`='',`Order XHTML Delivery Notes`='' ,`Order Balance Net Amount`=0,`Order Balance Tax Amount`=0,`Order Balance Total Amount`=0 ,`Order Outstanding Balance Net Amount`=0,`Order Outstanding Balance Tax Amount`=0,`Order Outstanding Balance Total Amount`=0,`Order Profit Amount`=0,`Order Cancel Note`=%s  where `Order Key`=%d"
				//     ,$no_shipped
				, prepare_mysql ( $this->data ['Order Cancelled Date'] )
				, prepare_mysql ( $this->data ['Order Current Payment State'] )
				, prepare_mysql ( $this->data ['Order Current Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML State'] )
				, prepare_mysql ( $this->data ['Order Cancel Note'] )

				, $this->id );
			if (! mysql_query( $sql ))
				exit ( "$sql arror can not update cancel\n" );

			$sql = sprintf( "update `Order Transaction Fact` set `Consolidated`='Yes',`Current Dispatching State`='Cancelled',`Current Payment State`='Cancelled' where `Order Key`=%d ", $this->id );
			mysql_query( $sql );
			$sql = sprintf( "update `Order No Product Transaction Fact` set `State`='Cancelled'  where `Order Key`=%d ", $this->id );
			mysql_query( $sql );



			foreach ($this->get_delivery_notes_objects() as $dn) {
				$dn->cancel($note,$date);
			}

			$customer=new Customer($this->data['Order Customer Key']);
			$customer->editor=$this->editor;
			$customer->add_history_order_cancelled($this);
			$this->update_deals_usage();
			$this->cancelled=true;

		}



	}


	function suspend($note='',$date=false) {

		$this->suspended=false;
		if (preg_match('/Dispatched/',$this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order can not be suspended, because has already been dispatched');

		}
		elseif (preg_match('/Suspended/',$this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order is cancelled');

		}
		elseif (preg_match('/Suspended/',$this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order is already suspended');

		}
		else {

			if (!$date)
				$date=date('Y-m-d H:i:s');
			$this->data ['Order Suspended Date'] = $date;

			$this->data ['Order Suspend Note'] = $note;

			$this->data ['Order Current Payment State'] = 'No Applicable';
			$this->data ['Order Current Dispatch State'] = 'Suspended';
			$this->data ['Order Current XHTML Dispatch State'] = _('Suspended');
			$this->data ['Order Current XHTML State'] = _( 'Order Suspended' );
			$this->data ['Order XHTML Invoices'] = '';
			$this->data ['Order XHTML Delivery Notes'] = '';
			$this->data ['Order Balance Total Amount'] = 0;
			$this->data ['Order Balance Net Amount'] = 0;
			$this->data ['Order Balance Tax Amount'] = 0;
			$this->data ['Order Outstanding Balance Total Amount'] = 0;
			$this->data ['Order Outstanding Balance Net Amount'] = 0;
			$this->data ['Order Outstanding Balance Tax Amount'] = 0;



			$sql = sprintf( "update `Order Dimension` set `Order Suspended Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML State`=%s,`Order XHTML Invoices`='',`Order XHTML Delivery Notes`='' ,`Order Balance Net Amount`=0,`Order Balance Tax Amount`=0,`Order Balance Total Amount`=0 ,`Order Outstanding Balance Net Amount`=0,`Order Outstanding Balance Tax Amount`=0,`Order Outstanding Balance Total Amount`=0,`Order Profit Amount`=0,`Order Suspend Note`=%s  where `Order Key`=%d"
				, prepare_mysql ( $this->data ['Order Suspended Date'] )
				, prepare_mysql ( $this->data ['Order Current Payment State'] )
				, prepare_mysql ( $this->data ['Order Current Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML State'] )
				, prepare_mysql ( $this->data ['Order Suspend Note'] )

				, $this->id );
			mysql_query( $sql );

			$sql = sprintf( "update `Order Transaction Fact` set `Current Dispatching State`='Suspended',`Current Payment State`='No Applicable' where `Order Key`=%d ", $this->id );
			mysql_query( $sql );
			$sql = sprintf( "update `Order No Product Transaction Fact` set `State`='Suspended'  where `Order Key`=%d ", $this->id );
			mysql_query( $sql );

			foreach ($this->get_delivery_notes_objects() as $dn) {
				$dn->suspend($note,$date);
			}

			$customer=new Customer($this->data['Order Customer Key']);
			$customer->editor=$this->editor;
			$customer->add_history_order_suspended($this);
			$this->suspended=true;

		}



	}


	function no_payment_applicable() {




		$this->data ['Order Current Payment State'] = 'No Applicable';
		$this->data ['Order Current Dispatch State'] = 'Dispatched';

		$dn_txt=_('Dispatched');
		if ($this->data ['Order Type'] == 'Order') {
			$dn_txt = "No value order, Dispatched";
		}



		$sql = sprintf( "update `Order Dimension` set `Order Current XHTML State`=%s where `Order Key`=%d", prepare_mysql ( $dn_txt ), $this->id );
		if (! mysql_query( $sql ))
			exit ( "arror can not update no_payment_applicable\n" );


		$sql = sprintf( "update `Order Dimension` set `Order Current Payment State`=%s ,`Order Current Dispatch State`=%s where `Order Key`=%d", prepare_mysql ( $this->data ['Order Current Payment State'] ), prepare_mysql ( $this->data ['Order Current Dispatch State'] ), $this->id );
		if (! mysql_query( $sql ))
			exit ( "arror can not update no_payment_applicable\n" );

		$sql = sprintf( "update `Order Transaction Fact` set `Consolidated`='Yes',`Current Payment State`=%s ,`Current Dispatching State`=%s where `Order Key`=%d", prepare_mysql ( $this->data ['Order Current Payment State'] ), prepare_mysql ( $this->data ['Order Current Dispatch State'] ), $this->id );
		if (! mysql_query( $sql ))
			exit ( "arror can not update no_payment_applicabl 3e\n" );

	}

	function delete_transaction($otf_key) {
		$sql=sprintf("delete from `Order Transaction Fact` where `Order Transaction Fact Key`=%d",$otf_key);
		mysql_query($sql);
	}


function authorize_all(){
$sql=sprintf("update  `Order Transaction Fact` set `Current Autorized to Sell Quantity`=`Order Quantity`  where `Order Key`=%d",$this->id);
		mysql_query($sql);

//print $sql;

}


	function add_order_transaction($data,$historic=false) {

		if (!isset($data ['ship to key'])) {
			$ship_to_keys=preg_split('/,/',$this->data['Order Ship To Keys']);
			$ship_to_key=$ship_to_keys[0];

		} else
			$ship_to_key=$data ['ship to key'];


		$tax_code=$this->data['Order Tax Code'];
		$tax_rate=$this->data['Order Tax Rate'];

		if (array_key_exists('tax_code',$data))
			$tax_code=$data['tax_code'];
		if (array_key_exists('tax_rate',$data))
			$tax_rate=$data['tax_rate'];

		if (isset($data['Order Type']))
			$order_type=$data['Order Type'];
		else
			$order_type=$this->data['Order Type'];

		if (array_key_exists('qty',$data)) {
			$quantity=$data ['qty'];
			$quantity_set=true;

		} else {
			$quantity=0;
			$quantity_set=false;
		}

		if (array_key_exists('qty',$data)) {
			$quantity=$data ['qty'];
			$quantity_set=true;

		} else {
			$quantity=0;
			$quantity_set=false;
		}



		if (array_key_exists('bonus qty',$data)) {
			$bonus_quantity=$data ['bonus qty'];
			$bonus_quantity_set=true;
		} else {
			$bonus_quantity=0;
			$bonus_quantity_set=false;

		}

		$gross_discounts=0;


		if ($historic) {

			// add transacction

			$total_quantity=$quantity+$bonus_quantity;
			if ($total_quantity==0) {
				return array(
					'updated'=>false
				);

			}

			$product=new Product('id',$data['Product Key']);
			$gross=$quantity*$product->data['Product History Price'];
			$estimated_weight=$total_quantity*$product->data['Product Gross Weight'];
			$gross_discounts=0;
			$sql = sprintf( "insert into `Order Transaction Fact` (`Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Estimated Weight`,`Order Date`,`Backlog Date`,`Order Last Updated Date`,
                             `Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
                             `Current Dispatching State`,`Current Payment State`,`Customer Key`,`Order Key`,`Order Public ID`,`Order Quantity`,`Ship To Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Metadata`,`Store Key`,`Units Per Case`,`Customer Message`)
                             values (%f,%s,%f,%s,%s,%s,%s,%s,%s,
                             %d,%d,%s,%d,%d,
                             %s,%s,%s,%s,%s,%s,%s,%.2f,%.2f,%s,%s,%f,'') ",
				$bonus_quantity,
				prepare_mysql($order_type),
				$tax_rate,
				prepare_mysql ($tax_code),
				prepare_mysql ( $this->data ['Order Currency'] ),
				$estimated_weight,
				prepare_mysql ( $data ['date'] ),
				prepare_mysql ( $data ['date'] ),
				prepare_mysql ( $data ['date'] ),
				$product->id,
				$product->data['Product ID'],
				prepare_mysql($product->data['Product Code']),
				$product->data['Product Family Key'],
				$product->data['Product Main Department Key'],
				prepare_mysql ( $data ['Current Dispatching State'] ),
				prepare_mysql ( $data ['Current Payment State'] ),
				prepare_mysql ( $this->data['Order Customer Key' ] ),
				prepare_mysql ( $this->data ['Order Key'] ),
				prepare_mysql ( $this->data ['Order Public ID'] ),
				$quantity,
				prepare_mysql ( $ship_to_key ),
				$gross,
				$gross_discounts,
				prepare_mysql ( $data ['Metadata'] ,false),
				prepare_mysql ( $this->data ['Order Store Key'] ),
				(isset($data ['units_per_case'])?$data ['units_per_case']:'')

			);
			if (! mysql_query( $sql ))
				exit ( "$sql can not update order trwansiocion facrt after invoice 1223" );
			$otf_key=mysql_insert_id();

		}else
		{



			if (!in_array($this->data['Order Current Dispatch State'],array('In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Packed')) ) {
				return array(
					'updated'=>false,

				);
			}








			$sql=sprintf("select `Order Bonus Quantity`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Fact Key` from `Order Transaction Fact` OTF where `Order Key`=%d and `Product Key`=%d ",
				$this->id,
				$data ['Product Key']);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_array($res)) {

				$old_quantity=$row['Order Quantity'];
				$old_bonus_quantity=$row['Order Bonus Quantity'];
				if (!$quantity_set) {
					$quantity=$old_quantity;
				}
				if (!$bonus_quantity_set) {
					$bonus_quantity=$old_bonus_quantity;
				}
				$total_quantity=$quantity+$bonus_quantity;


				//    print "\n**** $old_quantity $old_bonus_quantity   ;  ($quantity_set,$bonus_quantity_set) ; QTY    $quantity ==     $total_quantity\n";

				if ($total_quantity==0) {

					$this->delete_transaction($row['Order Transaction Fact Key']);
					$otf_key=0;
					$gross=0;

				}
				else {



					$product=new Product('id',$data['Product Key']);
					$estimated_weight=$total_quantity*$product->data['Product Gross Weight'];
					$gross=$quantity*$product->data['Product History Price'];




					$sql = sprintf( "update`Order Transaction Fact` set  `Estimated Weight`=%s,`Order Quantity`=%f,`Current Autorized to Sell Quantity`=%f,`Order Bonus Quantity`=%f,`Order Last Updated Date`=%s,`Order Transaction Gross Amount`=%f ,`Order Transaction Total Discount Amount`=%f  where `Order Transaction Fact Key`=%d ",
						$estimated_weight ,
						$quantity,
						$quantity,
						$bonus_quantity,
						prepare_mysql ( $data ['date'] ),
						$gross,
						$gross_discounts,
						$row['Order Transaction Fact Key']

					);
					mysql_query($sql);
					//print "$sql\n";

					$otf_key=$row['Order Transaction Fact Key'];





					//    print "$sql  $otf_key  \n";
					//    exit;
				}

			}
			else {
				// transacion with this product not  found
				$total_quantity=$quantity+$bonus_quantity;

				if ($total_quantity==0) {
					return array(
						'updated'=>false
					);
				}

				$product=new Product('id',$data['Product Key']);
				$gross=$quantity*$product->data['Product History Price'];
				$estimated_weight=$total_quantity*$product->data['Product Gross Weight'];


				$sql = sprintf( "insert into `Order Transaction Fact` (`Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Estimated Weight`,`Order Date`,`Backlog Date`,`Order Last Updated Date`,
                                 `Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
                                 `Current Dispatching State`,`Current Payment State`,`Customer Key`,`Order Key`,`Order Public ID`,`Order Quantity`,`Ship To Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Metadata`,`Store Key`,`Units Per Case`,`Customer Message`)
                                 values (%f,%s,%f,%s,%s,%s,%s,%s,%s,
                                 %d,%d,%s,%d,%d,
                                 %s,%s,%s,%s,%s,%s,%s,%.2f,%.2f,%s,%s,%f,'')   ",
			
					$bonus_quantity,
					prepare_mysql($order_type),
					$tax_rate,
					prepare_mysql ($tax_code),
					prepare_mysql ( $this->data ['Order Currency'] ),
					$estimated_weight ,
					prepare_mysql ( $data ['date'] ),
					prepare_mysql ( $data ['date'] ),
					prepare_mysql ( $data ['date'] ),
					$product->id,
					$product->data['Product ID'],
					prepare_mysql($product->data['Product Code']),
					$product->data['Product Family Key'],
					$product->data['Product Main Department Key'],
					prepare_mysql ( $data ['Current Dispatching State'] ),
					prepare_mysql ( $data ['Current Payment State'] ),
					prepare_mysql ( $this->data['Order Customer Key' ] ),
					prepare_mysql ( $this->data ['Order Key'] ),
					prepare_mysql ( $this->data ['Order Public ID'] ),
					$quantity,
					prepare_mysql ( $ship_to_key ),
					$gross,
					$gross_discounts,
					prepare_mysql ( $data ['Metadata'] ,false),
					prepare_mysql ( $this->data ['Order Store Key'] ),
					$product->data['Product Units Per Case']
				);
				//print "$sql\n";
				if (! mysql_query( $sql ))
					exit ( "$sql can not update order trwansiocion facrt after invoice 1223" );
				$otf_key=mysql_insert_id();
				
				
				

			}
			
			
			
			
					if (in_array($this->data['Order Current Dispatch State'],array('Ready to Pick','Picking & Packing','Packed')) ) {
				
				
				$dn_keys=$this->get_delivery_notes_ids();
				$dn_key=array_pop($dn_keys);
				$dn=new DeliveryNote($dn_key);
				$dn->update_inventory_transaction_fact($otf_key,$quantity);
				
				
			}

		}



		if (array_key_exists('Supplier Metadata', $data)) {

			$sql = sprintf( "update`Order Transaction Fact` set  `Supplier Metadata`=%s  where `Order Transaction Fact Key`=%d ",
				prepare_mysql($data['Supplier Metadata']),
				$otf_key

			);
			//        print "$sql\n";
			mysql_query($sql);
		}


		if (!$this->skip_update_after_individual_transaction) {



			$this->update_discounts();
			$this->update_item_totals_from_order_transactions();

			$this->update_shipping();
			$this->update_charges();
			$this->update_item_totals_from_order_transactions();

			$this->update_no_normal_totals();
			$this->update_totals_from_order_transactions();
			$this->update_number_items();


		}

		//print "xx $gross $gross_discounts ";

		return array(
			'updated'=>true,
			'otf_key'=>$otf_key,
			'to_charge'=>money($gross-$gross_discounts,$this->data['Order Currency']),
			'qty'=>$quantity,
			'bonus qty'=>$bonus_quantity,
			'discount_percentage'=>($gross_discounts>0?percentage($gross_discounts,$gross,$fixed=1,$error_txt='NA',$psign=''):'')
		);

		//  print "$sql\n";


	}







	function get_discounts($data, $customer_id, $date) {

		$family = array ();
		foreach ( $data as $item ) {
			$nodeal [$item ['product_id']] = _ ( 'No deal Available' );
			if (! isset ( $family [$item ['family_id']] ))
				$family [$item ['family_id']] = 1;
			else
				$family [$item ['family_id']] ++;
		}


		foreach ( $data as $item ) {
			$sql = sprintf( "select * from `Deal Metadata Dimension` where `Deal Metadata Allowance Type`='Percentage Off' and  `Deal Metadata Allowance Target`='Product' and `Deal Metadata Allowance Target Key`=%d and %s BETWEEN `Deal Metadata Begin Date` and  `Deal Metadata Expiration Date` ", $item ['product_id'], prepare_mysql ( $date ) );

			$result = & $this->db->query ( $sql );
			while ( $row = $result->fetchRow () ) {

				$metadata = split( ',', $row ['deal allowance metadata'] );
				if ($row ['deal allowance type'] == 'Percentage Off') {
					print "percentage off ";
					if (preg_match( '/Quantity Ordered$/i', $row ['deal terms type'] )) { //Depending on the quantity ordered
						// Family trigger -------------------------------------------------


						if ($row ['deal trigger'] == 'Family' and $row ['deal trigger key'] == $item ['family_id']) {
							print $family [$item ['family_id']] . '  ' . $metadata [0] . " family target\n";
							if ($family [$item ['family_id']] >= $metadata [0]) {
								$deal [$item ['product_id']] [] = array ('description' => $row ['deal description'], 'awollance' => $row ['deal allowance type'], 'discount_amount' => $metadata [1] * $item ['amount'], 'target' => $row ['deal allowance target'], 'trigger' => $row ['deal trigger'], 'terms' => $row ['deal terms type'], 'add' => 0, 'use' => 1 );
							} else
								$nodeal [$item ['product_id']] .= '; ' . _ ( 'Not enought products ordered' ) . ". " . $family [$item ['family_id']] . "/" . $metadata [0];
						} //_______________________________________________________________|
						// Product selft trigger -------------------------------------------------
						elseif ($row ['deal trigger'] == 'Product' and $row ['deal trigger key'] == $item ['product_id']) {
							if ($item ['qty'] >= $metadata [0]) {
								$deal [$item ['product_id']] [] = array ('description' => $row ['deal description'], 'awollance' => $row ['deal allowance type'], 'discount_amount' => $metadata [1] * $item ['amount'], 'target' => $row ['deal allowance target'], 'trigger' => $row ['deal trigger'], 'terms' => $row ['deal terms type'], 'add' => 0, 'use' => 1 );
							}
						} //________________________________________________________________|
						// Other Product  trigger -------------------------------------------------
						elseif ($row ['deal trigger'] == 'Product' and $row ['deal trigger key'] != $item ['product_id']) {

							if (isset ( $data [$row ['deal trigger key']] ))
								$qty = $data [$row ['deal trigger key']] ['qty'];
							else
								$qty = 0;

							if ($qty >= $metadata [0]) {
								$deal [$item ['product_id']] [] = array ('description' => $row ['deal description'], 'awollance' => $row ['deal allowance type'], 'discount_amount' => $metadata [1] * $item ['amount'], 'target' => $row ['deal allowance target'], 'trigger' => $row ['deal trigger'], 'terms' => $row ['deal terms type'], 'add' => 0, 'use' => 1 );
							}
						} //________________________________________________________________|


					} //end Depending quantity ordered
					if (preg_match( '/Order Interval$/i', $row ['deal terms type'] )) { //Depending on the order interval


						//get order interval;
						$customer = new Customer ( $customer_id );
						if ($customer->get ( 'order within', $metadata [0] )) {
							$deal [$item ['product_id']] [] = array ('description' => $row ['deal description'], 'discount_amount' => $metadata [1] * $item ['amount'] );
						} else {
							if ($customer->get ( 'customer orders' ) == 0)
								$nodeal [$item ['product_id']] .= '; ' . _ ( "No previous orders" );
							else
								$nodeal [$item ['product_id']] .= '; ' . _ ( "Last order not within" ) . ' ' . $metadata [0];
						}

					} //end Depending ordwer interval;


				} else if ($row ['deal allowance type'] == 'Get Free') {

						if ($row ['deal trigger'] == 'Product' and $row ['deal trigger key'] != $item ['product_id']) {
							$valid_orders = floor( $item ['qty'] / $metadata [0] );
							$free_qty = $valid_orders * $metadata [1];
							$deal [$item ['product_id']] [] = array ('target' => $row ['deal allowance target type'], 'trigger' => $row ['deal trigger'], 'terms' => $row ['deal terms type'], 'add' => $free_qty, 'discount_amount' => $free_qty * $item ['case_price'] );
						}

					} //end Get Free


			}

		}

		foreach ( $nodeal as $key => $value ) {
			if (preg_match( '/\;/', $value ))
				$nodeal [$key] = _trim ( preg_replace( '/.*\|\;/', '', $value ) );
			else
				$nodeal [$key] = _trim ( preg_replace( '/\|/', '', $value ) );
		}

		foreach ( $deal as $key => $value ) {
			if ($value ['allowance'] == 'Percentage Off') {
				if ($data [$key] ['discount'] < $value ['discount_amount'])
					$data [$key] ['discount'] = $value ['discount_amount'];

			}

		}
		foreach ( $deal as $key => $value ) {
			if ($value ['allowance'] == 'Get Free') {
				if ($data [$key] ['get_free'] < $value ['add'])
					$data [$key] ['get_free'] = $value ['add'];

			}

		}





		if (count( $deal ) > 0)
			exit ('Count deal is zero');

		//       $sql=sprintf("select * from `Deal Metadata Dimension` where `Allowance Type`='Percentage Off' and  `Triger`='Product' and `Trigger Key`=%d ",$item['product_id']);
		//       $result =& $this->db->query($sql);
		//       while($row=$result->fetchRow()){
		//  $deal=new DealMetadataMetadataMetadataMetadata($row['deal key']);


		//  $discount_function = create_function("$data,$customer_id,$date", $row['deal metadata']);
		//  $discount[$item['product_id']][$row['deal key']]['discount']=$discount_function($data,$customer,$date);
		//  $discount[$item['product_id']][$row['deal key']]['deal key']=$row['deal key'];
		//       }


		//     }
		return $data;
	}

	function create_order_header() {


		//calculate the order total
		$this->data ['Order Items Gross Amount'] = 0;
		$this->data ['Order Items Discount Amount'] = 0;
		//     $sql="select sum(`Order Transaction Gross Amount`) as gross,sum(`Order Transaction Total Discount Amount`) from `Order Transaction Fact` where "


		$sql = sprintf( "insert into `Order Dimension` (`Order Customer Order Number`,`Order Tax Code`,`Order Tax Rate`,
                         `Order Main Country 2 Alpha Code`,
                         `Order Main World Region Code`,
                         `Order Main Country Code`,
                         `Order Main Town`,
                         `Order Main Postal Code`,

                         `Order Customer Contact Name`,`Order For`,`Order File As`,`Order Date`,`Order Last Updated Date`,`Order Public ID`,`Order Store Key`,`Order Store Code`,`Order Main Source Type`,`Order Customer Key`,`Order Customer Name`,`Order Current Dispatch State`,`Order Current Payment State`,`Order Current XHTML State`,`Order Customer Message`,`Order Original Data MIME Type`,`Order Items Gross Amount`,`Order Items Discount Amount`,`Order Original Metadata`,`Order XHTML Store`,`Order Type`,`Order Currency`,`Order Currency Exchange`,`Order Original Data Filename`,`Order Original Data Source`) values
                         (%d,%s,%f,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s ,%.2f,%.2f,%s,%s,%s,%s,   %f,%s,%s)",
			$this->data ['Order Customer Order Number'],
			// $this->data ['Order Ship To Key To Deliver'],
			prepare_mysql ($this->data ['Order Tax Code'] ),
			$this->data ['Order Tax Rate'],

			prepare_mysql ( $this->data ['Order Main Country 2 Alpha Code'] ),
			prepare_mysql ( $this->data ['Order Main World Region Code'] ),
			prepare_mysql ( $this->data ['Order Main Country Code'] ),
			prepare_mysql ( $this->data ['Order Main Town'] ),
			prepare_mysql ( $this->data ['Order Main Postal Code'] ),



			prepare_mysql ( $this->data ['Order Customer Contact Name'],false ),
			prepare_mysql ( $this->data ['Order For'] ),
			prepare_mysql ( $this->data ['Order File As'] ),
			prepare_mysql ( $this->data ['Order Date'] ),
			prepare_mysql ( $this->data ['Order Date'] ),
			prepare_mysql ( $this->data ['Order Public ID'] ),
			prepare_mysql ( $this->data ['Order Store Key'] ),
			prepare_mysql ( $this->data ['Order Store Code'] ),

			prepare_mysql ( $this->data ['Order Main Source Type'] ),
			prepare_mysql ( $this->data ['Order Customer Key'] ),
			prepare_mysql ( $this->data ['Order Customer Name'] ,false),
			prepare_mysql ( $this->data ['Order Current Dispatch State'] ),
			prepare_mysql ( $this->data ['Order Current Payment State'] ),
			prepare_mysql ( $this->data ['Order Current XHTML State'] ),
			prepare_mysql ( $this->data ['Order Customer Message'] ),
			prepare_mysql ( $this->data ['Order Original Data MIME Type'] ),
			//prepare_mysql ( $this->data ['Order XHTML Ship Tos'] ,false),
			//prepare_mysql ( $this->data ['Order Ship To Keys'],false),

			// prepare_mysql ( $this->data ['Order Ship To Country Code'],false ),

			$this->data ['Order Items Gross Amount'],
			$this->data ['Order Items Discount Amount'],
			prepare_mysql ( $this->data ['Order Original Metadata'] ),
			prepare_mysql ( $this->data ['Order XHTML Store'] ),
			prepare_mysql ( $this->data ['Order Type'] ),
			prepare_mysql( $this->data ['Order Currency'] ),
			$this->data ['Order Currency Exchange'],
			prepare_mysql( $this->data ['Order Original Data Filename'] ),
			prepare_mysql( $this->data ['Order Original Data Source'] )
		)

		;

		if (mysql_query( $sql )) {
			$this->id = mysql_insert_id();
			$this->data ['Order Key'] = $this->id;
		}
		else {
			exit ( "$sql  Error coan not create order header");
		}

	}



	function get_data($key, $id) {
		if ($key == 'id') {
			$sql = sprintf( "select * from `Order Dimension` where `Order Key`=%d", $id );
			$result = mysql_query( $sql );
			if ($this->data = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				$this->id = $this->data ['Order Key'];
			}
			
			
		}
		elseif ($key == 'public id' or $key == 'public_id') {
			$sql = sprintf( "select * from `Order Dimension` where `Order Public ID`=%s", prepare_mysql ( $id ) );
			$result = mysql_query( $sql );
			//print "$sql\n";
			if ($this->data = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				$this->id = $this->data ['Order Key'];
			}

		}

	}

	function get($key = '') {

		if (array_key_exists( $key, $this->data ))
			return $this->data [$key];

		if (preg_match('/^(Invoiced Total Net Adjust|Invoiced Total Tax Adjust|Invoiced Refund Net|Invoiced Refund Tax|Total|Items|Invoiced Items|Invoiced Tax|Invoiced Net|Invoiced Charges|Invoiced Shipping|(Shipping |Charges )?Net).*(Amount)$/',$key)) {

			$amount='Order '.$key;

			return money($this->data[$amount],$this->data['Order Currency']);
		}
		if (preg_match('/^Number Items$/',$key)) {

			$amount='Order '.$key;

			return number($this->data[$amount]);
		}


		switch ($key) {
		case('Order Out of Stock Amount'):
			return $this->data['Order Out of Stock Net Amount']+$this->data['Order Out of Stock Tax Amount'];
		case('Out of Stock Amount'):
			return money($this->data['Order Out of Stock Net Amount']+$this->data['Order Out of Stock Tax Amount'],$this->data['Order Currency']);
		case('Invoiced Total Tax Amount'):
			return money($this->data['Order Invoiced Tax Amount']+$this->data['Order Invoiced Refund Tax Amount'],$this->data['Order Currency']);
			break;
		case('Out of Stock Net Amount'):
			return money($this->data['Order Out of Stock Net Amount'],$this->data['Order Currency']);
			break;

		case('Invoiced Total Net Amount'):
			return money($this->data['Order Invoiced Net Amount']+$this->data['Order Invoiced Refund Net Amount'],$this->data['Order Currency']);
			break;
		case('Invoiced Total Amount'):
			return money($this->data['Order Invoiced Net Amount']+$this->data['Order Invoiced Tax Amount']+$this->data['Order Invoiced Refund Net Amount']+$this->data['Order Invoiced Refund Tax Amount'],$this->data['Order Currency']);
			break;
		case('Shipping And Handing Net Amount'):
			return money($this->data['Order Shipping Net Amount']+$this->data['Order Charges Net Amount']);
			break;
		case('Date'):
			return strftime('%x',strtotime($this->data['Order Date']));
			break;
		case('Cancel Date'):
			return strftime('%x',strtotime($this->data['Order Cancelled Date']));
			break;
		case('Suspended Date'):
			return strftime('%x',strtotime($this->data['Order Suspended Date']));
			break;

		case ('Order Main Ship To Key') :
			$sql = sprintf( "select `Ship To Key`,count(*) as  num from `Order Transaction Fact` where `Order Key`=%d group by `Ship To Key` order by num desc limit 1", $this->id );
			$res = mysql_query( $sql );
			if ($row2 = mysql_fetch_array( $res, MYSQL_ASSOC )) {
				return $row2 ['Ship To Key'];
			} else
				return '';

			break;
		case ('Weight'):
			if ($this->data['Order Current Dispatch State']=='Dispatched') {
				if ($this->data['Order Weight']=='')
					return weight($this->data['Order Dispatched Estimated Weight']);
				else
					return weight($this->data['Order Weight']);
			} else {
				return weight($this->data['Order Estimated Weight']);
			}
			break;



		case('Current Dispatch State'):
			switch ($key) {
			case 'In Process':
				return _('In Process');
				break;
			case 'In Process by Customer':
				return _('In Process by Customer');
				break;
			case 'Submitted by Customer':
				return _('Submitted by Customer');
				break;
			case 'Ready to Pick':
				return _('Ready to Pick');
				break;
			case 'Picking & Packing':
				return _('Picking & Packing');
				break;
			case 'Ready to Ship':
				return _('Ready to Ship');
				break;
			case 'Dispatched':
				return _('Dispatched');
				break;
			case 'Unknown':
				return _('Unknown');
				break;
			case 'Packing':
				return _('Packing');
				break;
			case 'Cancelled':
				return _('Cancelled');
				break;
			case 'Suspended':
				return _('Suspended');
				break;

			default:
				return $this->data['Order Current Dispatch State'];
				break;
			}


		}
		$_key = ucwords( $key );
		if (array_key_exists( $_key, $this->data ))
			return $this->data [$_key];

		return false;
	}

	function distribute_costs_old() {
		$sql = "select * from `Order Transaction Fact` where `Invoice Key`=" . $this->data ['Invoice Key'];
		$result = mysql_query( $sql );
		$total_weight = 0;
		$weight_factor = array ();
		$total_charge = 0;
		$charge_factor = array ();
		$items = 0;
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			$items ++;
			$weight = $row ['Estimated Weight'];
			$total_weight += $weight;
			$weight_factor [$row ['Invoice Line']] = $weight;

			$charge = $row ['Order Transaction Gross Amount'];
			$total_charge += $charge;
			$charge_factor [$row ['Invoice Line']] = $charge;

		}
		if ($items == 0)
			return;

		foreach ( $weight_factor as $line_number => $factor ) {
			if ($total_weight == 0)
				$value = $this->data ['Invoice Shipping Net Amount'] * $factor / $items;
			else
				$value = $this->data ['Invoice Shipping Net Amount'] * $factor / $total_weight;
			$sql = sprintf( "update `Order Transaction Fact` set `Invoice Transaction Shipping Amount`=%.4f where `Invoice Key`=%d and  `Invoice Line`=%d ", $value, $this->data ['Invoice Key'], $line_number );
			if (! mysql_query( $sql ))
				exit ( "$sql error dfsdfs doerde.pgp" );
		}
		$total_tax = $this->data ['Invoice Items Tax Amount'] + $this->data ['Invoice Shipping Tax Amount'] + $this->data ['Invoice Charges Tax Amount'];

		foreach ( $charge_factor as $line_number => $factor ) {
			if ($total_charge == 0) {
				$charges = $this->data ['Invoice Charges Net Amount'] * $factor / $items;
				$vat = $total_tax * $factor / $items;

			} else {
				$charges = $this->data ['Invoice Charges Net Amount'] * $factor / $total_charge;
				$vat = $total_tax * $factor / $total_charge;

			}
			$sql = sprintf( "update `Order Transaction Fact` set `Invoice Transaction Charges Amount`=%.4f ,`Invoice Transaction Total Tax Amount`=%.4f  where `Invoice Key`=%d and  `Invoice Line`=%d ", $charges, $vat, $this->data ['Invoice Key'], $line_number );
			if (! mysql_query( $sql ))
				exit ( "$sql error dfsdfs 2 doerde.pgp" );
		}

	}



	function get_delivery_notes_ids() {
		$sql=sprintf("select `Delivery Note Key` from `Order Transaction Fact` where `Order Key`=%d group by `Delivery Note Key`",$this->id);
		//print "$sql\n";
		$res = mysql_query( $sql );
		$delivery_notes=array();
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Delivery Note Key']) {
				$delivery_notes[$row['Delivery Note Key']]=$row['Delivery Note Key'];
			}

		}
		return $delivery_notes;

	}
	function get_delivery_notes_objects() {
		$delivery_notes=array();
		$delivery_notes_ids=$this->get_delivery_notes_ids();
		foreach ($delivery_notes_ids as $delivery_notes_id) {
			$delivery_notes[$delivery_notes_id]=new DeliveryNote($delivery_notes_id);
		}
		return $delivery_notes;
	}


	function get_invoices_ids() {
		$invoices=array();


		$sql=sprintf("select `Invoice Key` from `Order Transaction Fact` where `Order Key`=%d group by `Invoice Key`",$this->id);
		$res = mysql_query( $sql );
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Invoice Key']) {
				$invoices[$row['Invoice Key']]=$row['Invoice Key'];
			}
		}
		$sql=sprintf("select `Refund Key` from `Order Transaction Fact` where `Order Key`=%d group by `Refund Key`",$this->id);
		$res = mysql_query( $sql );
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Refund Key']) {
				$invoices[$row['Refund Key']]=$row['Refund Key'];
			}
		}


		return $invoices;

	}
	function get_invoices_objects() {
		$invoices=array();
		$invoices_ids=$this->get_invoices_ids();
		foreach ($invoices_ids as $order_id) {
			$invoices[$order_id]=new Invoice($order_id);
		}
		return $invoices;
	}






	function load($key = '', $args = '') {
		switch ($key) {
		case('Order Total Net Amount'):
			$sql = "select sum(`Order Transaction Gross Amount`) as gross,sum(`Order Transaction Total Discount Amount`) as discount, sum(`Invoice Transaction Shipping Amount`) as shipping,sum(`Invoice Transaction Charges Amount`) as charges ,sum(`Invoice Transaction Item Tax Amount`+`Invoice Transaction Shipping Tax Amount`+`Invoice Transaction Charges Tax Amount`) as tax   from `Order Transaction Fact` where  `Order Key`=" . $this->data ['Order Key'];
			$result = mysql_query( $sql );
			if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				$total = $row ['gross'] + $row ['shipping'] + $row ['charges'] - $row ['discount'];
			} else
				$total=0;

			return $total;
			break;

		case ('totals') :
			exit("load totals in order should not be called");


			//      print "+++".$this->data ['Order Current Payment State']."+++\n";

			if ($this->data ['Order Current Payment State'] == 'Waiting Payment') {
				$this->update_item_totals_from_order_transactions();

				$this->update_totals_from_order_transactions();
			} else
				$this->update_totals_from_invoice_transactions();

			break;

		case ('items') :
			$sql = sprintf( "select * from `Order Transaction Fact` where `Order Key`=%d", $this->id );
			$res = mysql_query( $sql );
			$this->items = array ();
			while ( $row2 = mysql_fetch_array( $res, MYSQL_ASSOC ) ) {
				$this->items [] = $row2;
			}

			break;
		}

	}

	function get_date($key = '', $tipo = 'dt') {
		if (isset ( $this->dates ['ts_' . $key] ) and is_numeric( $this->dates ['ts_' . $key] )) {

			switch ($tipo) {
			case ('dt') :
			default :
				return strftime( "%e %B %Y %H:%M", $porder ['date_expected'] );
			}
		} else
			return false;
	}



	function set($tipo, $data) {
		global $_order_status;
		switch ($tipo) {
		case ('Order Refund Amount') :
			$this->data ['Charges Amount'] = $data;
			$sql = sprintf( "update `Order Dimension` set `Order Refund Amount`=%.2f  where `Order Key`=%d", $this->data ['r Charges Amount'], $this->id );
			if (! mysql_query( $sql ))
				exit ( "arror can not update cancel\n" );

			break;
		case ('date_submited') :

			if ($this->data ['status'] < 10) {

				$datetime = prepare_mysql_datetime ( $data ['sdate'] . ' ' . $data ['stime'] );
				if ($datetime ['ok']) {
					$this->data ['tipo'] = 1;
					$this->data ['status_id'] = 10;
					$this->data ['status'] = $_order_status [$this->data ['status_id']];

					$this->data ['date_submited'] = $datetime ['ts'];
					$this->data ['dates'] ['submited'] = strftime( "%e %b %Y %H:%M", $datetime ['ts'] );
					$this->save ( $tipo );

					$this->save_history ( 'submit', array ('date' => 'NOW', 'user_id' => $data ['user_id'] ) );

					return array ('ok' => true );
				} else
					return array ('ok' => false, 'msg' => _ ( 'wrong date' ) . ' ' . $data ['sdate'] . ' ' . $data ['stime'] );
			} else {
				return array ('ok' => false, 'msg' => _ ( 'Order is already submited' ) );

			}

			break;
		case ('date_expected') :
			$datetime = prepare_mysql_datetime ( $data ['date'] . ' 12:00:00', 'datetime' );
			if ($datetime ['ok']) {
				if ($this->data ['status_id'] >= 10 and $this->data ['status_id'] < 80) {

					$old_value = $this->data ['date_expected'];
					$this->data ['date_expected'] = $datetime ['ts'];
					$this->data ['dates'] ['expected'] = strftime( "%e %b %Y", $datetime ['ts'] );
					$this->save ( 'date_expected' );

					if (! isset ( $data ['history'] ) or $data ['history'])
						$this->save_history ( 'date_expected', array ('date' => 'NOW()', 'user_id' => $data ['user_id'], 'old_value' => $old_value ) );
					return array ('ok' => true, 'date' => $this->data ['dates'] ['expected'] );
				} else
					return array ('ok' => false, 'msg' => _ ( 'Order not submited or already received' ) . " " . $this->data ['status_id'] );
			} else
				return array ('ok' => false, 'msg' => _ ( 'Wrong date' ) );

			break;
		case ('date_received') :
			$datetime = prepare_mysql_datetime ( $data ['date'] . " " . $data ['time'], 'datetime' );

			if ($datetime ['ok']) {
				if ($this->data ['status'] < 20) {
					$this->data ['date_received'] = $datetime ['ts'];
					$this->data ['dates'] ['received'] = strftime( "%e %B %Y %H:%M", $datetime ['ts'] );

					//   print "caca";
					$done_by = $data ['done_by'];
					if (count( $done_by ) == 0 or ! is_array( $done_by ))
						return array ('ok' => false, 'msg' => _ ( 'Error, indicate who receive the order' ) );
					$this->data ['received_by'] = array ();
					$received_list = '';
					foreach ( $done_by as $id => $value ) {
						$staff = new staff ( $id );
						if ($staff->id) {
							$this->data ['received_by'] [$id] = $staff;
							$received_list = ', ' . $staff->data ['alias'];
						} else
							return array ('ok' => false, 'msg' => _ ( 'Error, staff id not found' ) );

						unset ( $staff );
					}

					$this->data ['received_by_list'] = preg_replace( '/^\,\s*/', '', $received_list );
					$this->data ['status_id'] = 80;
					$this->data ['status'] = $_order_status [$this->data ['status_id']];

					$this->save ( $tipo );
					if (! isset ( $data ['history'] ) or $data ['history'])
						$this->save_history ( $tipo, array ('date' => 'NOW()', 'user_id' => $data ['user_id'] ) );
					return array ('ok' => true );
				} else
					return array ('ok' => false, 'msg' => _ ( 'Already received' ) );
			} else
				return array ('ok' => false, 'msg' => _ ( 'Wrong date' ) );
		case ('date_checked') :
			$datetime = prepare_mysql_datetime ( $data ['date'] . " " . $data ['time'], 'datetime' );
			if ($datetime ['ok']) {
				if ($this->data ['status'] < 80 or $this->data ['status'] >= 90) {
					$this->data ['date_checked'] = $datetime ['ts'];
					$this->data ['dates'] ['checked'] = strftime( "%e %B %Y %H:%M", $datetime ['ts'] );
					$this->data ['status_id'] = 90;
					$this->data ['status'] = $_order_status [$this->data ['status_id']];

					$done_by = $data ['done_by'];

					if (count( $done_by ) == 0 or ! is_array( $done_by ))
						return array ('ok' => false, 'msg' => _ ( 'Error, indicate who checked the order' ) );
					$this->data ['checked_by'] = array ();
					$received_list = '';
					foreach ( $done_by as $id => $value ) {
						$staff = new staff ( $id );
						if ($staff->id) {
							$this->data ['checked_by'] [$id] = $staff;
							$received_list = ', ' . $staff->data ['alias'];
						} else
							return array ('ok' => false, 'msg' => _ ( 'Error, staff id not found' ) );

						unset ( $staff );
					}

					$this->data ['checked_by_list'] = preg_replace( '/^\,\s*/', '', $received_list );

					$this->save ( $tipo );
					if (! isset ( $data ['history'] ) or $data ['history'])
						$this->save_history ( $tipo, array ('date' => 'NOW()', 'user_id' => $data ['user_id'] ) );
					return array ('ok' => true );
				} else
					return array ('ok' => false, 'msg' => _ ( 'Already checked or not received yet' ) );
			} else
				return array ('ok' => false, 'msg' => _ ( 'Wrong date' ) );
			break;
		case ('date_consolidated') :
			$datetime = prepare_mysql_datetime ( $data ['date'] . " " . $data ['time'], 'datetime' );
			if ($this->data ['status'] <= 90 and $this->data ['status'] < 100) {
				$this->save ( $tipo, $datetime );
				$this->get_data ();
				if (! isset ( $data ['history'] ) or $data ['history'])
					$this->save_history ( $tipo, array ('date' => 'NOW()', 'user_id' => $data ['user_id'], 'done_by' => $data ['done_by'] ) );
				return array ('ok' => true );
			} else
				return array ('ok' => false, 'msg' => _ ( 'Error can not be consolidated' ) );
			break;
		case ('date_cancelled') :
			$datetime = prepare_mysql_datetime ( $data ['rdate'] . " " . $data ['time'], 'date' );
			if ($this->data ['status'] < 80) {
				$this->save ( $tipo, $datetime );
				$this->get_data ();
				if (! isset ( $data ['history'] ) or $data ['history'])
					$this->save_history ( $tipo, array ('date' => 'NOW()', 'user_id' => $data ['user_id'] ) );
				return array ('ok' => true );
			} else
				return array ('ok' => false, 'msg' => _ ( 'Error, order already received' ) );
			break;

		}
		return array ('ok' => false, 'msg' => _ ( 'Operation not found' ) . " $tipo" );
	}
	function save($key) {
		switch ($key) {

		case ('items') :
			if ($this->tipo = 'po') {
				$sql = sprintf( "update porden set items=%d,total=%.2f,goods=%.2f", $this->data ['items'], $this->data ['total'], $this->data ['goods'] );
				mysql_query( $sql );

			}

			break;
		case ('date_submited') :
			if ($this->tipo = 'po') {
				$sql = sprintf( "update porden set date_submited='%s' , tipo=%d, status_id=%d where id=%d", date( "Y-m-d H:i:s", strtotime( "@" . $this->data ['date_submited'] ) ), $this->data ['tipo'], $this->data ['status_id'], $this->id );
			}
			mysql_query( $sql );
			break;
		case ('date_expected') :
			if ($this->tipo = 'po') {
				$sql = sprintf( "update porden set date_expected='%s' where id=%d", date( "Y-m-d H:i:s", strtotime( "@" . $this->data ['date_expected'] ) ), $this->id );
				// print $sql;
			}
			mysql_query( $sql );
			break;
		case ('date_received') :
			if ($this->tipo = 'po') {
				$sql = sprintf( "update porden set date_received='%s',status_id=%d   where id=%d", date( "Y-m-d H:i:s", strtotime( "@" . $this->data ['date_received'] ) ), $this->data ['status_id'], $this->id );
				mysql_query( $sql );
				$num_receivers = count( $this->data ['received_by'] );
				if ($num_receivers > 0) {
					$share = 1 / $num_receivers;
					foreach ( $this->data ['received_by'] as $key => $value ) {
						$sql = sprintf( "insert into porden_receiver (po_id,staff_id,share) values (%d,%d,%f)", $this->id, $key, $share );
						//     print "$sql ";
						mysql_query( $sql );
					}
				}

			}
			// mysql_query($sql);
			break;
		case ('date_checked') :
			if ($this->tipo = 'po') {
				$sql = sprintf( "update porden set date_checked='%s' ,status_id=%d   where id=%d", date( "Y-m-d H:i:s", strtotime( "@" . $this->data ['date_checked'] ) ), $this->data ['status_id'], $this->id );
				mysql_query( $sql );

				$num_checkers = count( $this->data ['checked_by'] );
				if ($num_checkers > 0) {
					$share = 1 / $num_checkers;
					foreach ( $this->data ['checked_by'] as $key => $value ) {
						$sql = sprintf( "insert into porden_checker (po_id,staff_id,share) values (%d,%d,%f)", $this->id, $key, $share );
						//     print "$sql ";
						mysql_query( $sql );
					}
				}

			}

			break;
		case ('date_consolidated') :
			if ($this->tipo = 'po') {
				$sql = sprintf( "update porden set date_consolidated=%s , consolidated_by=%d ,status_id=%d   where id=%d", date( "Y-m-d H:i:s", strtotime( "@" . $this->data ['date_consolidated'] ) ), $this->data ['consolidated_by'], $this->data ['status_id'], $this->id );
			}
			mysql_query( $sql );
			break;
		case ('vateable') :
			$value = $this->get ( $key );
			$sql = sprintf( "update %s set %s=%d where id=%d", $this->db_table, $key, $value, $this->id );
			//print $sql;
			mysql_query( $sql );

		}
	}

	function save_history($key, $data) {
		switch ($key) {
		case ('date_submited') :
			if ($this->tipo = 'po') {
				$note = _ ( 'submited' ) . " " . $this->data ['dates'] ['submited'];
				$sql = sprintf( "insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PO',%d,'SDATE',NULL,'NEW',%d,NULL,'%d',%s)", $data ['date'], $this->id, $data ['user_id'], $this->data ['date_submited'], prepare_mysql ( $note ) );
			}
			mysql_query( $sql );
			break;
		case ('date_expected') :
			if ($this->tipo = 'po') {
				$note = _ ( 'expected' ) . " " . $this->data ['dates'] ['expected'];
				$sql = sprintf( "insert into history (date,sujeto,sujeto_id,objeto,objeto_id,tipo,staff_id,old_value,new_value,note) values (%s,'PO',%d,'EDATE',NULL,'CHG',%d,'%d','%d',%s)", $data ['date'], $this->id, $data ['user_id'], $data ['old_value'], $this->data ['date_expected'], prepare_mysql ( $note ) );
			}
			mysql_query( $sql );
			break;

		}
	}


	function update_field_switcher($field,$value,$options='') {

		switch ($field) {
		case('Order XHTML Invoices'):
			$this->update_xhtml_invoices();
			break;
		case('Order XHTML Delivery Notes'):
			$this->update_xhtml_delivery_notes();
			break;
		default:
			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {
				if ($value!=$this->data[$field]) {
					$this->update_field($field,$value,$options);
				}
			}
		}
	}


	function update_old($values, $args = '') {
		$res = array ();

		foreach ( $values as $data ) {

			$key = $data ['key'];
			$value = $data ['value'];
			$res [$key] = array ('ok' => false, 'msg' => '' );

			switch ($key) {
			case('Order XHTML Invoices'):
				$this->update_xhtml_invoices();
				break;
			case('Order XHTML Delivery Notes'):
				$this->update_xhtml_delivery_notes();
				break;

			case ('vateable') :
				if ($value)
					$this->data [$key] = 1;
				else
					$this->data [$key] = 0;
				break;
			default :
				$res [$key] = array ('res' => 2, 'new_value' => '', 'desc' => 'Unkwown key' );
			}
			if (preg_match( '/save/', $args ))
				$this->save ( $key );

		}
		return $res;
	}
	function update_xhtml_invoices() {
		$prefix='';
		$this->data ['Order XHTML Invoices'] ='';
		foreach ($this->get_invoices_objects() as $invoice) {
			$this->data ['Order XHTML Invoices'] .= sprintf( '%s <a href="invoice.php?id=%d">%s</a>, ', $prefix, $invoice->data ['Invoice Key'], $invoice->data ['Invoice Public ID'] );
		}
		$this->data ['Order XHTML Invoices'] =_trim(preg_replace('/\, $/','',$this->data ['Order XHTML Invoices']));
		$sql=sprintf("update `Order Dimension` set `Order XHTML Invoices`=%s where `Order Key`=%d "
			,prepare_mysql($this->data['Order XHTML Invoices'])
			,$this->id
		);
		mysql_query($sql);
	}

	function update_xhtml_delivery_notes() {
		$prefix='';
		$this->data ['Order XHTML Delivery Notes'] ='';
		foreach ($this->get_delivery_notes_objects() as $delivery_note) {
			$this->data ['Order XHTML Delivery Notes'] .= sprintf( '%s <a href="dn.php?id=%d">%s</a>, ', $prefix, $delivery_note->data ['Delivery Note Key'], $delivery_note->data ['Delivery Note ID'] );
		}
		$this->data ['Order XHTML Delivery Notes'] =_trim(preg_replace('/\, $/','',$this->data ['Order XHTML Delivery Notes']));

		$sql=sprintf("update `Order Dimension` set `Order XHTML Delivery Notes`=%s where `Order Key`=%d "
			,prepare_mysql($this->data['Order XHTML Delivery Notes'])
			,$this->id
		);
		mysql_query($sql);
	}


	function cutomer_rankings() {
		$sql = sprintf( "select `Customer Key` as id,`Customer Orders` as orders, (select count(*) from `Customer Dimension` as TC where TC.`Customer Orders`<C.`Customer Orders`) as better,(select count(DISTINCT `Customer Key` ) from `Customer Dimension`) total  from `Customer Dimension` as C order by `Customer Orders` desc ;" );

		$orders = - 99999;
		$position = 0;

		$result = mysql_query( $sql );
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {

			if ($row ['orders'] != $orders) {
				$position ++;
				$orders = $row ['orders'];
			}
			$better_than = $row ['better'];
			$total = $row ['total'];
			if ($total > 0)
				$top = sprintf( "%f", 100 * (1.0 - ($better_than / $total)) );
			else
				$top = 'null';
			$id = $row ['id'];
			$sql = sprintf( "update `Customer Dimension` set `Customer Orders Top Percentage`=%s,`Customer Orders Position`=%d,`Customer Has More Orders Than`=%d where `Customer Key`=%d", $top, $position, $better_than, $id );
			// print "$sql\n";
			mysql_query( $sql );
		}
	}

	function compare_addresses($cdata) {

		//check if the addresses are the same:
		$diff_result = array_diff( $cdata ['address_data'], $cdata ['shipping_data'] );

		if (count( $diff_result ) == 0) {

			$this->same_address = true;
			$this->same_contact = true;
			$this->same_company = true;

			$this->same_telephone = true;

		} else {


			$percentage = array ('address1' => 1, 'town' => 1, 'country' => 1, 'country_d1' => 1, 'postcode' => 1 );
			$percentage_address = array ();

			foreach ( $diff_result as $key => $value ) {
				similar_text( $cdata ['shipping_data'] [$key], $cdata ['address_data'] [$key], $p );
				$percentage [$key] = $p / 100;
				if (preg_match( '/address1|town|^country$|postcode|country_d1/i', $key ))
					$percentage_address [$key] = $p / 100;
			}
			if (count( $percentage ) == 0)
				$avg_percentage = 1;
			else
				$avg_percentage = average ( $percentage );

			if (count( $percentage_address ) == 0)
				$avg_percentage_address = 1;
			else
				$avg_percentage_address = average ( $percentage_address );

			//   print "AVG DIFF O:$avg_percentage A:$avg_percentage_address \n";


			if ($cdata ['shipping_data'] ['name'] == '' or ! array_key_exists( 'name', $diff_result ))
				$this->same_contact = true;
			else {
				$_max = 1000000;
				$irand = mt_rand( 0, 1000000 );
				$rand = $irand / $_max;
				if ($rand < $percentage ['name'] and $percentage ['name'] > .90) {
					$this->same_contact = true;

				} else
					$this->same_contact = false;
			}
			if ($cdata ['shipping_data'] ['company'] == '' or ! array_key_exists( 'company', $diff_result ))
				$this->same_company = true;
			else {
				$_max = 1000000;
				$irand = mt_rand( 0, 1000000 );
				$rand = $irand / $_max;

				if ($rand < $percentage ['company'] and $percentage ['company'] > .90) {
					$this->same_company = true;
				} else
					$this->same_company = false;
			}

			if (array_key_exists( 'telephone', $diff_result ))
				$this->same_telephone = false;
			else
				$this->same_telephone = true;

			if ($avg_percentage_address == 1)
				$this->same_address = true;
			else
				$this->same_address = false;


		}

	}


	function update_product_sales() {
		return;
		if ($this->skip_update_product_sales)
			return;




		$stores=array();
		$family=array();
		$departments=array();
		$sql = "select OTF.`Product Key` ,`Product Family Key`,`Product Store Key` from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)where `Order Key`=" . $this->data ['Order Key']." group by OTF.`Product Key`";
		$result = mysql_query( $sql );
		//   print $sql;
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			$product=new Product($row['Product Key']);
			$product->load('sales');
			$family[$row['Product Family Key']]=true;
			$store[$row['Product Store Key']]=true;
		}
		foreach ($family as $key=>$val) {
			$family=new Family($key);
			$family->update_sales_data();
			$sql = sprintf("select `Product Department Key`  from `Product Family Department Bridge` where `Product Family Key`=%d" ,$key);
			$result = mysql_query( $sql );
			while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				$departments[$row['Product Department Key']]=true;
			}

		}
		foreach ($departments as $key=>$val) {
			$department=new Department($key);
			$department->update_sales_data();
		}


		foreach ($store as $key=>$val) {
			$store=new Store($key);
			$store->load('sales');
		}

	}

	/*
      function: get_items_totals_by_adding_transactions
      Calculate the totals of the ORIGINAL order from the data in Order Transaction Fact
    */

	function get_items_totals_by_adding_transactions() {



		$sql = "select sum(`Estimated Dispatched Weight`) as disp_estimated_weight,sum(`Estimated Weight`) as estimated_weight,sum(`Weight`) as weight,sum(`Transaction Tax Rate`*(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`)) as tax, sum(`Order Transaction Gross Amount`) as gross,sum(`Order Transaction Total Discount Amount`) as discount, sum(`Invoice Transaction Shipping Amount`) as shipping,sum(`Invoice Transaction Charges Amount`) as charges    from `Order Transaction Fact` where  `Order Key`=" . $this->data ['Order Key'];
		// print "$sql\n";
		$result = mysql_query( $sql );
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			$total_items_net = $row ['gross']  - $row ['discount'];
			$this->data ['Order Items Gross Amount'] = $row ['gross'];
			$this->data ['Order Items Discount Amount'] = $row ['discount'];
			$this->data ['Order Items Net Amount'] = $total_items_net;
			$this->data ['Order Items Tax Amount']= $row ['tax'];
			$this->data ['Order Items Total Amount']= $this->data ['Order Items Net Amount'] +$this->data ['Order Items Tax Amount'];
			$this->data ['Order Estimated Weight']= $row ['estimated_weight'];
			$this->data ['Order Dispatched Estimated Weight']= $row ['disp_estimated_weight'];



		}

	}

	/*
      function: accept
      Accetp order
    */

	function accept() {
		$this->data['Order Balance Net Amount']=$this->data ['Order Items Net Amount'];
		$this->data['Order Balance Tax Amount']=$this->data ['Order Items Tax Amount'];
		$this->data['Order Balance Total Amount']=$this->data ['Order Items Total Amount'];

	}


	function update_no_normal_totals($args='') {



		$this->data['Order Balance Net Amount']=0;
		$this->data['Order Balance Tax Amount']=0;
		$this->data['Order Balance Total Amount']=0;
		$this->data['Order Outstanding Balance Net Amount']=0;
		$this->data['Order Outstanding Balance Tax Amount']=0;
		$this->data['Order Outstanding Balance Total Amount']=0;
		$this->data['Order Invoiced Refund Net Amount']=0;
		$this->data['Order Invoiced Refund Tax Amount']=0;
		$this->data['Order Invoiced Refund Notes']='';

		$sql = "select
               sum(IFNULL(`Cost Supplier`,0)+IFNULL(`Cost Storing`,0)+IFNULL(`Cost Handing`,0)+IFNULL(`Cost Shipping`,0))as costs,
               sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as net,
               sum(`Invoice Transaction Item Tax Amount`) as tax,
               sum(`Invoice Transaction Net Refund Amount`) as ref_net,
               sum(`Invoice Transaction Tax Refund Amount`) as ref_tax,
               sum(`Invoice Transaction Outstanding Net Balance`) as ob_net ,
               sum(`Invoice Transaction Outstanding Tax Balance`) as ob_tax ,
               sum(`Invoice Transaction Outstanding Refund Net Balance`) as ref_ob_net ,
               sum(`Invoice Transaction Outstanding Refund Tax Balance`) as ref_ob_tax ,

               sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as inv_items,
               sum(`Invoice Transaction Shipping Amount`) as inv_shp,
               sum(`Invoice Transaction Charges Amount`) as inv_charges,
               sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Invoice Transaction Shipping Amount`+`Invoice Transaction Charges Amount`) as inv_net,
               sum(`Invoice Transaction Item Tax Amount`+`Invoice Transaction Shipping Tax Amount`+`Invoice Transaction Charges Tax Amount`) as inv_tax,


               sum(if(`Order Quantity`>0, `No Shipped Due Out of Stock`*(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`)/`Order Quantity`,0)) as out_of_stock_net,
               sum(if(`Order Quantity`>0, `No Shipped Due Out of Stock`*`Transaction Tax Rate`*(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`)/`Order Quantity`,0)) as out_of_stock_tax



               from `Order Transaction Fact`    where  `Order Key`=" . $this->data ['Order Key'];

		$result = mysql_query( $sql );
		//print "\n$sql\n";
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			$this->data['Order Balance Net Amount']=$row['net']+$row['ref_net'];
			$this->data['Order Balance Tax Amount']=$row['tax']+$row['ref_tax'];
			$this->data['Order Balance Total Amount']=$this->data['Order Balance Net Amount']+$this->data['Order Balance Tax Amount'];
			$this->data['Order Outstanding Balance Net Amount']=$row['ob_net']+$row['ref_ob_net'];
			$this->data['Order Outstanding Balance Tax Amount']=$row['ob_tax']+$row['ref_ob_tax'];
			$this->data['Order Outstanding Balance Total Amount']=$this->data['Order Outstanding Balance Net Amount']+$this->data['Order Outstanding Balance Tax Amount'];

			$this->data['Order Tax Refund Amount']=$row['ref_tax'];
			$this->data['Order Net Refund Amount']=$row['ref_net'];

			$this->data['Order Invoiced Items Amount']=$row['inv_items'];
			$this->data['Order Invoiced Shipping Amount']=$row['inv_shp'];
			$this->data['Order Invoiced Charges Amount']=$row['inv_charges'];
			$this->data['Order Invoiced Net Amount']=$row['inv_net'];
			$this->data['Order Invoiced Tax Amount']=$row['inv_tax'];
			$this->data['Order Invoiced Refund Net Amount']=$row['ref_net'];
			$this->data['Order Invoiced Refund Tax Amount']=$row['ref_tax'];


			$this->data['Order Out of Stock Net Amount']=$row['out_of_stock_net'];
			$this->data['Order Out of Stock Tax Amount']=$row['out_of_stock_tax'];




			$this->data['Order Profit Amount']= $this->data['Order Balance Net Amount']-$this->data['Order Outstanding Balance Net Amount']- $row['costs'];

		}





		$sql = sprintf("select * from `Order No Product Transaction Fact` where `Order Key`=%d" , $this->data ['Order Key']);
		//print "$sql\n";
		$result = mysql_query( $sql );
		while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			$this->data['Order Balance Net Amount']+=$row['Transaction Invoice Net Amount'];
			$this->data['Order Balance Tax Amount']+=$row['Transaction Invoice Tax Amount'];
			$this->data['Order Balance Total Amount']+=$row['Transaction Invoice Net Amount']+$row['Transaction Invoice Tax Amount'];
			$this->data['Order Outstanding Balance Net Amount']+=$row['Transaction Outstandind Net Amount Balance'];
			$this->data['Order Outstanding Balance Tax Amount']+=$row['Transaction Outstandind Tax Amount Balance'];
			$this->data['Order Outstanding Balance Total Amount']+=$row['Transaction Outstandind Net Amount Balance']+$row['Transaction Outstandind Tax Amount Balance'];

			//  if($row['Invoice Key']){
			//$this->data['Order Invoiced Net Amount']+=($row['Transaction Invoice Net Amount']);
			//$this->data['Order Invoiced Tax Amount']+=($row['Transaction Invoice Tax Amount']);
			// }
			if ($row['Transaction Type']=='Refund' or $row['Transaction Type']=='Credit') {
				$this->data['Order Tax Refund Amount']+=$row['Transaction Invoice Tax Amount'];
				$this->data['Order Net Refund Amount']+=$row['Transaction Invoice Net Amount'];
			} else if ($row['Transaction Type']=='Adjust') {
					$this->data['Order Invoiced Total Net Adjust Amount']+=$row['Transaction Invoice Net Amount'];
					$this->data['Order Invoiced Total Tax Adjust Amount']+=$row['Transaction Invoice Tax Amount'];
				}

		}

		$sql = sprintf("select * from `Order No Product Transaction Fact` where `Transaction Type` in ('Refund','Credit') and `Affected Order Key`=%d" , $this->data ['Order Key']);

		$result = mysql_query( $sql );
		while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {

			$this->data['Order Invoiced Refund Net Amount']+=$row['Transaction Invoice Net Amount'];
			$this->data['Order Invoiced Refund Tax Amount']+=$row['Transaction Invoice Tax Amount'];
			if ($row['Transaction Description']!='')
				$this->data['Order Invoiced Refund Notes'].='<br/>'.$row['Transaction Description'];
		}
		$this->data['Order Invoiced Refund Notes']=preg_replace('/<br\/>/','',$this->data['Order Invoiced Refund Notes']);

		$sql=sprintf("update `Order Dimension` set
                     `Order Balance Net Amount`=%.2f,`Order Balance Tax Amount`=%.2f,`Order Balance Total Amount`=%.2f,
                     `Order Outstanding Balance Net Amount`=%.2f,`Order Outstanding Balance Tax Amount`=%.2f,`Order Outstanding Balance Total Amount`=%.2f,
                     `Order Tax Refund Amount`=%.2f,`Order Net Refund Amount`=%.2f,`Order Profit Amount`=%.2f,
                     `Order Invoiced Items Amount`=%.2f,`Order Invoiced Shipping Amount`=%.2f,`Order Invoiced Charges Amount`=%.2f,
                     `Order Invoiced Net Amount`=%.2f,`Order Invoiced Tax Amount`=%.2f,
                     `Order Out of Stock Net Amount`=%.2f,
                     `Order Out of Stock Tax Amount`=%.2f,
                     `Order Invoiced Refund Net Amount`=%.2f,
                     `Order Invoiced Refund Tax Amount`=%.2f,
                     `Order Invoiced Refund Notes`=%s,
                     `Order Invoiced Total Net Adjust Amount`=%.2f,
                     `Order Invoiced Total Tax Adjust Amount`=%.2f

                     where `Order Key`=%d",
			$this->data['Order Balance Net Amount'],
			$this->data['Order Balance Tax Amount'],
			$this->data['Order Balance Total Amount'],

			$this->data['Order Outstanding Balance Net Amount'],
			$this->data['Order Outstanding Balance Tax Amount'],
			$this->data['Order Outstanding Balance Total Amount'],

			$this->data['Order Tax Refund Amount'],
			$this->data['Order Net Refund Amount'],
			$this->data['Order Profit Amount'],

			$this->data['Order Invoiced Items Amount'],
			$this->data['Order Invoiced Shipping Amount'],
			$this->data['Order Invoiced Charges Amount'],

			$this->data['Order Invoiced Net Amount'],
			$this->data['Order Invoiced Tax Amount'],
			$this->data['Order Out of Stock Net Amount'],
			$this->data['Order Out of Stock Tax Amount'],

			$this->data['Order Invoiced Refund Net Amount'],
			$this->data['Order Invoiced Refund Tax Amount'],
			prepare_mysql($this->data['Order Invoiced Refund Notes']),
			$this->data['Order Invoiced Total Net Adjust Amount'],
			$this->data['Order Invoiced Total Tax Adjust Amount'],
			$this->id
		);

		//  print "$sql\n";
		if (!mysql_query($sql))
			exit("ERROR $sql\n");




	}

	function update_invoices($args='') {
		global $myconf;
		$sql=sprintf("select `Invoice Key` from `Order Transaction Fact` where `Order Key`=%d group by `Invoice Key`",$this->id);

		$res = mysql_query( $sql );
		$this->invoices=array();
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Invoice Key']) {
				$invoice=new Invoice($row['Invoice Key']);
				$this->invoices[$row['Invoice Key']]=$invoice;
			}

		}
		//update no normal fields
		$this->data ['Order XHTML Invoices'] ='';
		foreach ($this->invoices as $invoice) {
			$this->data ['Order XHTML Invoices'] .= sprintf( '<a href="invoice.php?id=%d">%s</a>, ',$invoice->data ['Invoice Key'], $invoice->data ['Invoice Public ID'] );

		}
		$this->data ['Order XHTML Invoices'] =_trim(preg_replace('/\, $/','',$this->data ['Order XHTML Invoices']));
		//$where_dns=preg_replace('/\,$/',')',$where_dns);

		if (!preg_match('/no save/i',$args)) {
			$sql=sprintf("update `Order Dimension`  set `Order XHTML Invoices`=%s where `Order Key`=%d"
				,prepare_mysql($this->data ['Order XHTML Invoices'])
				,$this->id
			);

			mysql_query($sql);

		}

	}


	function update_delivery_notes($args='') {


		$this->update_xhtml_delivery_notes();


	}




	function update_customer_history() {
		//print $this->data['Order Current Dispatch State']."\n";
		$customer=new Customer ($this->data['Order Customer Key']);
		switch ($this->data['Order Current Dispatch State']) {

		case ('Picking & Packing'):
		case('Ready to Pick'):
		case('Ready to Ship'):
		case('Dispatched'):
			$customer->update_history_order_in_warehouse($this);
			break;
		default:

			break;
		}



	}

	function update_xhtml_dispatch_state() {

		$xhtml_dispatch_state='';

		$sql=sprintf("select DN.`Delivery Note Key`,DN.`Delivery Note ID`,`Delivery Note Faction Picked`,`Delivery Note Assigned Picker Alias`,`Delivery Note Faction Packed`,`Delivery Note Assigned Packer Alias` from `Order Transaction Fact` B  left join `Delivery Note Dimension` DN  on (DN.`Delivery Note Key`=B.`Delivery Note Key`) where `Order Key`=%d group by B.`Delivery Note Key`",$this->id);

		$res = mysql_query( $sql );
		$delivery_notes=array();
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Delivery Note Key']) {
				$status='';
				if ($row['Delivery Note Assigned Picker Alias']) {

					if ($row['Delivery Note Faction Picked']==1) {
						$_tmp=_('Picked');
					}else {
						$_tmp=_('Picking').'('.percentage($row['Delivery Note Faction Picked'],1,0).')';
					}
					$status.='<div id="dn_state'.$row['Delivery Note Key'].'">'.$_tmp.' <b>'.$row['Delivery Note Assigned Picker Alias'].'</b> </div>';
				}

				if ($row['Delivery Note Assigned Packer Alias']) {

					if ($row['Delivery Note Faction Packed']==1) {
						$_tmp=_('Packed');
					}else {
						$_tmp=_('Packing').'('.percentage($row['Delivery Note Faction Packed'],1,0).')';
					}
					$status.='<div id="dn_state'.$row['Delivery Note Key'].'">'.$_tmp.' <b>'.$row['Delivery Note Assigned Packer Alias'].'</b> </div>';
				}





				$xhtml_dispatch_state.=sprintf('<a href="dn.php?id=%d">%s</a> %s',$row['Delivery Note Key'],$row['Delivery Note ID'],$status);
			}

		}
		$this->data['Order Current XHTML Dispatch State']=$xhtml_dispatch_state;

		$sql=sprintf("update `Order Dimension` set `Order Current XHTML Dispatch State`=%s where `Order Key`=%d",
			prepare_mysql($xhtml_dispatch_state,false),
			$this->id
		);
		// print $sql;
		mysql_query($sql);

	}


	function update_dispatch_state() {
		//$sql = sprintf("select `Current Dispatching State` as state from `Order Transaction Fact` where `Order Key`=%d and `Order Transaction Type`!='Resend' order by `Current Payment State`",
		// $this->id);
		//print "$sql\n";
		//$result = mysql_query( $sql );
		//$array_state=array();
		//while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
		// $array_state[$row['state']]=$row['state'];
		//}

		//'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Packed','Ready to Ship','Dispatched','Unknown','Packing','Cancelled','Suspended'

		if (in_array($this->data['Order Current Dispatch State'],array('In Process by Customer','Submitted by Customer','Dispatched','Cancelled','Suspended')) )
			return;

		$old_dispatch_state=$this->data['Order Current Dispatch State'];

		$xhtml_dispatch_state='';

		$dispatch_state='Unknown';

		//

		$sql=sprintf("select `Delivery Note XHTML State`,`Delivery Note State`,DN.`Delivery Note Key`,DN.`Delivery Note ID`,`Delivery Note Faction Picked`,`Delivery Note Assigned Picker Alias`,`Delivery Note Faction Packed`,`Delivery Note Assigned Packer Alias` from `Order Transaction Fact` B  left join `Delivery Note Dimension` DN  on (DN.`Delivery Note Key`=B.`Delivery Note Key`)
		where `Order Key`=%d group by B.`Delivery Note Key`  order by Field (`Delivery Note State`,  'Dispatched','Cancelled','Cancelled to Restock','Approved' ,'Packed Done' , 'Packed','Ready to be Picked','Picker Assigned','Packer Assigned','Picker & Packer Assigned','Picked','Picking' ,'Packing' ,'Picking & Packing') ",$this->id);

		$res = mysql_query( $sql );
		$delivery_notes=array();
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {


			//print_r($row);
			if ($row['Delivery Note Key']) {
				if ($row['Delivery Note State']=='Ready to be Picked') {
					$dispatch_state='Ready to Pick';
				}elseif (in_array($row['Delivery Note State'],array('Picker & Packer Assigned','Picking & Packing','Packer Assigned','Ready to be Picked','Picker Assigned','Picking','Picked','Packing','Packed')) ) {
					$dispatch_state='Picking & Packing';

				}elseif ($row['Delivery Note State']=='Packed Done') {
					$dispatch_state='Packed';
				}elseif ($row['Delivery Note State']=='Approved') {
					$dispatch_state='Ready to Ship';
				}elseif ($row['Delivery Note State']=='Dispatched') {
					$dispatch_state='Dispatched';
				}else {
					$dispatch_state='Unknown';
				}

				$status=$row['Delivery Note XHTML State'];




				$xhtml_dispatch_state.=sprintf('<a href="dn.php?id=%d">%s</a> %s',$row['Delivery Note Key'],$row['Delivery Note ID'],$status);
			}

		}
		$this->data['Order Current XHTML Dispatch State']=$xhtml_dispatch_state;





		$sql=sprintf("update `Order Dimension` set `Order Current XHTML Dispatch State`=%s where `Order Key`=%d",
			prepare_mysql($xhtml_dispatch_state,false),
			$this->id
		);
		//print $sql.' '.$dispatch_state;
		//print $xhtml_dispatch_state.' xox '.$dispatch_state."\n =========\n";
		mysql_query($sql);




		$this->data['Order Current Dispatch State']=$dispatch_state;


		$this->data['Order Current XHTML State']=$this->calculate_state();
		if ($old_dispatch_state!=$this->data['Order Current Dispatch State']) {

			$sql=sprintf("update `Order Dimension` set `Order Current Dispatch State`=%s,`Order Current XHTML State`=%s  where `Order Key`=%d"
				,prepare_mysql($this->data['Order Current Dispatch State'])
				,prepare_mysql($this->data['Order Current XHTML State'])

				,$this->id
			);

			mysql_query($sql);
			$this->update_customer_history();
			$this->update_full_search();
		}

	}


	function set_order_as_dispatched($date) {

		// TODO dont set as dispatched until all the DN are dispatched (no inclide post transactions)

		$this->data['Order Current Dispatch State']='Dispatched';
		$this->data['Order Current Dispatch State']=_('Dispatched');
		$this->data['Order Current XHTML State']=$this->calculate_state();

		$sql=sprintf("update `Order Dimension` set `Order Dispatched Date`=%s , `Order Current XHTML Dispatch State`=%s ,`Order Current Dispatch State`=%s,`Order Current XHTML State`=%s  where `Order Key`=%d"
			,prepare_mysql($date)
			,prepare_mysql($this->data['Order Current XHTML Dispatch State'])
			,prepare_mysql($this->data['Order Current Dispatch State'])
			,prepare_mysql($this->data['Order Current XHTML State'])
			,$this->id
		);
		mysql_query($sql);

		$this->update_customer_history();
		$this->update_full_search();

	}



	function set_order_post_actions_as_dispatched($date) {

		// TODO dont set as dispatched until all the DN are dispatched (no inclide post transactions)

		$this->data['Order Current Dispatch State']='Dispatched';
		$this->data['Order Current Dispatch State']=_('Dispatched');
		$this->data['Order Current XHTML State']=$this->calculate_state();

		$sql=sprintf("update `Order Dimension` set `Order Post Transactions Dispatched Date`=%s , `Order Current XHTML Dispatch State`=%s ,`Order Current Dispatch State`=%s,`Order Current XHTML State`=%s  where `Order Key`=%d"
			,prepare_mysql($date)
			,prepare_mysql($this->data['Order Current XHTML Dispatch State'])
			,prepare_mysql($this->data['Order Current Dispatch State'])
			,prepare_mysql($this->data['Order Current XHTML State'])
			,$this->id
		);
		mysql_query($sql);
		$this->update_customer_history();
		$this->update_full_search();

	}




	function translate_payment_state($array_payment_state) {

		$payment_state='Unknown';
		if (count($array_payment_state)==1)
			switch (array_pop($array_payment_state)) {
			case 'Paid':
				$payment_state='Paid';
				break;
			case 'Waiting Payment':
				$payment_state='Waiting Payment';
				break;
			default:
				$payment_state='Unknown';
				break;
			}
		return $payment_state;
	}

	function update_payment_state() {
		$sql = sprintf("select `Current Payment State` as payment_state from `Order Transaction Fact` where `Order Key`=%d order by `Current Payment State`",
			$this->id);

		$result = mysql_query( $sql );
		$array_payment_state=array();
		while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			$array_payment_state[$row['payment_state']]=$row['payment_state'];
		}


		$this->data['Order Current Payment State']=$this->translate_payment_state($array_payment_state);
		$sql=sprintf("update `Order Dimension` set `Order Current Payment State`=%s ,`Order Current XHTML State`=%s  where `Order Key`=%d  "
			,prepare_mysql($this->data['Order Current Payment State'])
			,prepare_mysql($this->calculate_state())
			,$this->id);
		mysql_query($sql);


	}


	function calculate_state() {
		return _trim($this->data['Order Current Dispatch State'].', '.$this->data['Order Current Payment State']);
	}



	function update_item_totals_from_order_transactions() {
		if ($this->ghost_order or !$this->data ['Order Key'])
			return;
		$this->get_items_totals_by_adding_transactions();
		$sql = sprintf( "update `Order Dimension` set `Order Items Gross Amount`=%.2f, `Order Items Discount Amount`=%.2f, `Order Items Net Amount`=%.2f , `Order Items Tax Amount`=%.2f where  `Order Key`=%d "
			, $this->data ['Order Items Gross Amount']
			, $this->data ['Order Items Discount Amount']
			, $this->data ['Order Items Net Amount']
			, $this->data ['Order Items Tax Amount']
			, $this->data ['Order Key']
		);

		mysql_query( $sql );



	}


	function update_totals_from_order_transactions() {
		if ($this->ghost_order or !$this->data ['Order Key'])
			return;





		$this->data ['Order Total Tax Amount'] = $this->data ['Order Items Tax Amount'] + $this->data ['Order Shipping Tax Amount']+  $this->data ['Order Charges Tax Amount'];
		$this->data ['Order Total Net Amount']=$this->data ['Order Items Net Amount']+  ($this->data ['Order Shipping Net Amount']==''?0:$this->data ['Order Shipping Net Amount'])+  $this->data ['Order Charges Net Amount'];

		$this->data ['Order Total Amount'] = $this->data ['Order Total Tax Amount'] + $this->data ['Order Total Net Amount'];
		$this->data ['Order Total To Pay Amount'] = $this->data ['Order Total Amount'];

		$this->data ['Order Items Adjust Amount']=0;


		// print_r($this->data);

		$sql = sprintf( "update `Order Dimension` set
                         `Order Total Net Amount`=%.2f
                         ,`Order Total Tax Amount`=%.2f ,`Order Total Amount`=%.2f
                         , `Order Balance Total Amount`=%.2f
                         ,`Order Estimated Weight`=%f
                         ,`Order Dispatched Estimated Weight`=%f

                         where  `Order Key`=%d "
			, $this->data ['Order Total Net Amount']
			, $this->data ['Order Total Tax Amount']
			// , (is_numeric($this->data ['Order Shipping Net Amount'])?$this->data ['Order Shipping Net Amount']:'NULL')
			//, $this->data ['Order Shipping Tax Amount']

			// , $this->data ['Order Charges Net Amount']
			//, $this->data ['Order Charges Tax Amount']

			, $this->data ['Order Total Amount']
			, $this->data ['Order Total To Pay Amount']
			, $this->data ['Order Estimated Weight']
			, $this->data ['Order Dispatched Estimated Weight']
			, $this->data ['Order Key']
		);


		if (! mysql_query( $sql ))
			exit ( "$sql eroro2 con no update totals" );




	}




	function use_calculated_shipping() {

		$this->update_shipping_method('Calculated');
		$this->update_shipping();
		$this->updated=true;
		$this->update_item_totals_from_order_transactions();
		$this->get_items_totals_by_adding_transactions();
		$this->update_no_normal_totals('save');
		$this->update_totals_from_order_transactions();
		$this->new_value=$this->data['Order Shipping Net Amount'];

	}
	
	
		function use_calculated_items_charges() {

		$this->update_charges();
		$this->updated=true;
		$this->update_item_totals_from_order_transactions();
		$this->get_items_totals_by_adding_transactions();
		$this->update_no_normal_totals('save');
		$this->update_totals_from_order_transactions();
		$this->new_value=$this->data['Order Charges Net Amount'];

	}
	
	
	

	function update_shipping_amount($value) {
		$value=sprintf("%.2f",$value);;

		if ($value!=$this->data['Order Shipping Net Amount'] or $this->data['Order Shipping Method']!='On Demand') {
			$this->update_shipping_method('On Demand');
			$this->data['Order Shipping Net Amount']=$value;
			$this->update_shipping();

			$this->updated=true;
			$this->new_value=$value;

			$this->update_item_totals_from_order_transactions();
			$this->get_items_totals_by_adding_transactions();
			$this->update_no_normal_totals('save');
			$this->update_totals_from_order_transactions();

		}

	}





	function update_charges_amount($charge_data) {
	
	
	
		if ($charge_data['Charge Net Amount']!=$this->data['Order Charges Net Amount']) {

			$this->data['Order Charges Net Amount']=$charge_data['Charge Net Amount'];

			$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Charges" and `Delivery Note Key` IS NULL and `Invoice Key` IS NULL',
				$this->id
			);
			mysql_query($sql);
			// print "$sql\n";

			$total_charges_net=$charge_data['Charge Net Amount'];
			$total_charges_tax=$charge_data['Charge Tax Amount'];
			if ($charge_data['Charge Tax Amount']!=0 or $charge_data['Charge Net Amount']!=0) {
				$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Transaction Outstandind Net Amount Balance`,`Transaction Outstandind Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)  values (%d,%s,%s,%d,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
					$this->id,
					prepare_mysql($this->data['Order Date']),
					prepare_mysql('Charges'),
					$charge_data['Charge Key'],
					prepare_mysql($charge_data['Charge Description']),
					$charge_data['Charge Net Amount'],
					prepare_mysql($this->data['Order Tax Code']),
					$charge_data['Charge Tax Amount'],
					$charge_data['Charge Net Amount'],
					$charge_data['Charge Tax Amount'],
					prepare_mysql($this->data['Order Currency']),
					$this->data['Order Currency Exchange'],
					prepare_mysql($this->data['Order Original Metadata'])
				);

				//print ("$sql\n");
				mysql_query($sql);
			}




			$this->data['Order Charges Net Amount']=$total_charges_net;
			$this->data['Order Charges Tax Amount']=$total_charges_tax;


			$sql=sprintf("update `Order Dimension` set `Order Charges Net Amount`=%s ,`Order Charges Tax Amount`=%.2f where `Order Key`=%d"
				,$this->data['Order Charges Net Amount']
				,$this->data['Order Charges Tax Amount']
				,$this->id
			);
			mysql_query($sql);
			//print "*a $sql\n";

			// exit;

	$this->updated=true;
			$this->new_value=$this->data['Order Charges Net Amount'];

			$this->update_item_totals_from_order_transactions();
			$this->get_items_totals_by_adding_transactions();
			$this->update_no_normal_totals('save');
			$this->update_totals_from_order_transactions();




		}




	}







	// function get_ship_to_from_customer($customer_key){
	//  return $customer->get_ship_to($this->data['Order Date']);
	// }


	function set_data_from_customer($customer_key,$store_key=false) {


		$customer=new Customer($customer_key);
		if (!$store_key) {
			$store_key=$customer->data['Customer Store Key'];
		}



		$this->billing_address=new Address($customer->data['Customer Main Address Key']);
		$this->data ['Order Customer Key'] = $customer->id;
		$this->data ['Order Customer Name'] = $customer->data[ 'Customer Name' ];
		$this->data ['Order Customer Contact Name'] = $customer->data ['Customer Main Contact Name'];
		$this->data ['Order Main Country 2 Alpha Code'] = ($customer->data ['Customer Main Country 2 Alpha Code']==''?'XX':$customer->data ['Customer Main Country 2 Alpha Code']);
		$this->data ['Order Main Country Code'] = ($customer->data ['Customer Main Country Code']==''?'UNK':$customer->data ['Customer Main Country Code']);
		$this->data ['Order Main Postal Code'] = ($customer->data ['Customer Main Postal Code']==''?'':$customer->data ['Customer Main Postal Code']);
		$this->data ['Order Main Town'] = ($customer->data ['Customer Main Town']==''?'':$customer->data ['Customer Main Town']);

		$sql=sprintf("select `World Region Code` from kbase.`Country Dimension` where `Country Code`=%s",prepare_mysql($this->data ['Order Main Country Code']));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result))
			$this->data ['Order Main World Region Code'] = ($row['World Region Code']==''?'UNKN':$row['World Region Code']);
		else
			$this->data ['Order Main World Region Code'] = 'UNKN';


		$sql=sprintf("select `World Region Code` from kbase.`Country Dimension` where `Country Code`=%s",prepare_mysql($this->data ['Order Main Country Code']));
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result))
			$this->data ['Order Main World Region Code'] = ($row['World Region Code']==''?'UNKN':$row['World Region Code']);
		else
			$this->data ['Order Main World Region Code'] = 'UNKN';



		$this->data ['Order Customer Order Number']=$customer->get_number_of_orders()+1;
		if (!isset($this->data ['Order Tax Code'])) {
			if ($customer->data['Customer Tax Category Code']) {
				$tax_category=new TaxCategory('code',$customer->data['Customer Tax Category Code']);
				$this->data ['Order Tax Rate'] = $tax_category->data['Tax Category Rate'];
				$this->data ['Order Tax Code'] = $tax_category->data['Tax Category Code'];
			}
		}
		$this->set_data_from_store($store_key);
	}

	function set_data_from_store($store_key) {
		$store=new Store($store_key);
		if (!$store->id) {
			$this->error=true;
			return;
		}

		$this->data ['Order Store Key'] = $store->id;
		$this->data ['Order Store Code'] = $store->data[ 'Store Code' ];
		$this->data ['Order XHTML Store'] = sprintf( '<a href="store.php?id=%d">%s</a>', $store->id, $store->data[ 'Store Code' ] );
		$this->data ['Order Currency']=$store->data[ 'Store Currency Code' ];

		$this->public_id_format=$store->data[ 'Store Order Public ID Format' ];
		if (!isset($this->data ['Order Tax Code'])) {
			$tax_category=new TaxCategory($store->data['Store Tax Category Code']);
			$this->data ['Order Tax Rate'] = $tax_category->data['Tax Category Rate'];
			$this->data ['Order Tax Code'] = $tax_category->data['Tax Category Code'];
		}


		//$this->set_taxes($store->data['Store Tax Country Code']);


	}






	function next_public_id() {



		$sqla=sprintf("UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) where `Store Key`=%d"
			,$this->data['Order Store Key']);
		mysql_query($sqla);




		$public_id=mysql_insert_id();


		$this->data['Order Public ID']=sprintf($this->public_id_format,$public_id);
		$this->data['Order File As']=$this->prepare_file_as($this->data['Order Public ID']);
	}

	function get_next_line_number() {

		$sql=sprintf("select count(*) as num_lines from `Order Transaction Fact` where `Order Key`=%d ",$this->id);
		$res=mysql_query($sql);

		$line_number=1;
		if ($row=mysql_fetch_array($res))
			$line_number+=$row['num_lines'];
		return $line_number;


	}


	function categorize($args='') {
		$store=new store($this->data['Order Store Key']);


		if ($store->id==1) {
			$this->data['Order Category']=$store->data['Store Code'].'-'.$store->data['Store Home Country Short Name'];
			$this->data['Order Category Key']=2;
			if ($this->data['Order Main Country 2 Alpha Code']!=$store->data['Store Home Country Code 2 Alpha']) {
				$this->data['Order Category']=$store->data['Store Code'].'-Export';
				$this->data['Order Category Key']=4;

			}
			if ($this->data['Order For']=='Staff') {
				$this->data['Order Category']=$store->data['Store Code'].'-Staff';
				$this->data['Order Category Key']=3;

			}
			if ($this->data['Order For']=='Partner') {
				$this->data['Order Category']=$store->data['Store Code'].'-Partner';
				$this->data['Order Category Key']=5;

			}

		} else if ($store->id==2) {
				$this->data['Order Category']=$store->data['Store Code'].'-All';
				$this->data['Order Category Key']=7;


			}
		elseif ($store->id==3) {
			$this->data['Order Category']=$store->data['Store Code'].'-All';
			$this->data['Order Category Key']=9;

		}
		if (!preg_match('/nosave|no_save/i',$args)) {

			$sql = sprintf( "update `Order Dimension` set `Order Category`=%s ,`Order Category Key`=%d  where `Order Key`=%d"
				, prepare_mysql($this->data['Order Category'])
				, $this->data ['Order Category Key']
				, $this->data ['Order Key']
			);
			if (! mysql_query( $sql ))
				exit ( "$sql\n xcan not update order dimension after cat\n" );

		}



	}



	function update_tax() {


	}



	function update_shipping($dn_key=false) {


		if ($dn_key) {
			list($shipping,$shipping_key)=$this->get_shipping($dn_key);
		} else {
			list($shipping,$shipping_key)=$this->get_shipping();
		}
		if (!is_numeric($shipping)) {

			$this->data['Order Shipping Net Amount']='NULL';
			$this->data['Order Shipping Tax Amount']=0;
		} else {

			$this->data['Order Shipping Net Amount']=$shipping;
			$this->data['Order Shipping Tax Amount']=$shipping*$this->data['Order Tax Rate'];
		}




		$sql=sprintf("update `Order Dimension` set `Order Shipping Net Amount`=%.2f ,`Order Shipping Tax Amount`=%.2f where `Order Key`=%d"
			,$this->data['Order Shipping Net Amount']
			,$this->data['Order Shipping Tax Amount']
			,$this->id
		);
		mysql_query($sql);

		// print "$shipping $sql\n";
		$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Shipping"  and `Delivery Note Key` IS NULL and `Invoice Key` IS NULL',
			$this->id
		);
		mysql_query($sql);


		$sql=sprintf("select * from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Shipping'  and `Delivery Note Key`=%s and `Invoice Key` IS NULL",
			$this->id,
			prepare_mysql($dn_key)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$sql=sprintf("update `Order No Product Transaction Fact` set `Tax Category Code`=%s,`Transaction Net Amount`=%.2f ,Transaction Tax Amount`=%2.f,`Transaction Outstandind Net Amount Balance`=%.2f,`Transaction Outstandind Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d  ",
				prepare_mysql($this->data['Order Tax Code']),
				$this->data['Order Shipping Net Amount'],
				$this->data['Order Shipping Tax Amount'],
				$this->data['Order Shipping Net Amount'],
				$this->data['Order Shipping Tax Amount'],
				$row['Order No Product Transaction Fact Key']

			);


			mysql_query($sql);

		} else {
			$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Transaction Outstandind Net Amount Balance`,`Transaction Outstandind Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)  values (%d,%s,%s,%d,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
				$this->id,
				prepare_mysql($this->data['Order Date']),
				prepare_mysql('Shipping'),
				$shipping_key,
				prepare_mysql(_('Shipping')),
				$this->data['Order Shipping Net Amount'],
				prepare_mysql($this->data['Order Tax Code']),
				$this->data['Order Shipping Tax Amount'],
				$this->data['Order Shipping Net Amount'],
				$this->data['Order Shipping Tax Amount'],
				prepare_mysql($this->data['Order Currency']),
				$this->data['Order Currency Exchange'],
				prepare_mysql($this->data['Order Original Metadata'])
			);

			//print ("$sql\n");
			mysql_query($sql);
		}
		$this->update_no_normal_totals('save');

		$this->update_totals_from_order_transactions();

	}



	function update_charges($dn_key=false) {

		if (!$dn_key) {

			$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Charges" and `Delivery Note Key` IS NULL and `Invoice Key` IS NULL',
				$this->id
			);
		} else {
			$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Charges" and `Delivery Note Key`=%d and `Invoice Key` IS NULL',
				$this->id,
				$dn_key
			);


		}


		mysql_query($sql);

		$charges_array=$this->get_charges($dn_key);

		$total_charges_net=0;
		$total_charges_tax=0;
		foreach ($charges_array as $charge_data) {
			$total_charges_net+=$charge_data['Charge Net Amount'];
			$total_charges_tax+=$charge_data['Charge Tax Amount'];

			$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Transaction Outstandind Net Amount Balance`,`Transaction Outstandind Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)  values (%d,%s,%s,%d,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
				$this->id,
				prepare_mysql($this->data['Order Date']),
				prepare_mysql('Charges'),
				$charge_data['Charge Key'],
				prepare_mysql($charge_data['Charge Description']),
				$charge_data['Charge Net Amount'],
				prepare_mysql($this->data['Order Tax Code']),
				$charge_data['Charge Tax Amount'],
				$charge_data['Charge Net Amount'],
				$charge_data['Charge Tax Amount'],
				prepare_mysql($this->data['Order Currency']),
				$this->data['Order Currency Exchange'],
				prepare_mysql($this->data['Order Original Metadata'])
			);
			mysql_query($sql);


		}



		$this->data['Order Charges Net Amount']=$total_charges_net;
		$this->data['Order Charges Tax Amount']=$total_charges_tax;


		$sql=sprintf("update `Order Dimension` set `Order Charges Net Amount`=%s ,`Order Charges Tax Amount`=%.2f where `Order Key`=%d"
			,$this->data['Order Charges Net Amount']
			,$this->data['Order Charges Tax Amount']
			,$this->id
		);
		mysql_query($sql);
		// print "* $sql\n";


	}


	function get_charges($dn_key=false) {
		$sql=sprintf("select * from `Charge Dimension` where `Charge Trigger`='Order' and `Charge Trigger Key` in (0,%d)  "
			,$this->id
		);
		$res=mysql_query($sql);
		$charges=array();;
		while ($row=mysql_fetch_array($res)) {
			$apply_charge=false;
			if ($row['Charge Type']=='Amount') {
				$order_amount=$this->data[$row['Charge Terms Type']];
				if ($dn_key) {
					switch ($row['Charge Terms Type']) {
					case 'Order Items Gross Amount':
					default:
						$sql=sprintf("select sum( `Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)  ) as amount from `Order Transaction Fact` where `Order Key`=%d and `Delivery Note Key`=%d and `Order Quantity`!=0",
							$this->id,
							$dn_key
						);
						$res=mysql_query($sql);
						if ($row2=mysql_fetch_assoc($res)) {
							$order_amount=$row2['amount'];
						} else {
							$order_amount=0;
						}
						break;
					}
				}
				$terms_components=preg_split('/;/',$row['Charge Terms Metadata']);
				$operator=$terms_components[0];
				$amount=$terms_components[1];
				if ($this->data[$row['Charge Terms Type']]!=0) {
					switch ($operator) {
					case('<'):
						if ($order_amount<$amount)
							$apply_charge=true;
						break;
					case('>'):
						if ($order_amount>$amount)
							$apply_charge=true;
						break;
					case('<='):
						if ($order_amount<=$amount)
							$apply_charge=true;
						break;
					case('>='):
						if ($order_amount>=$amount)
							$apply_charge=true;
						break;
					}
				}

			}
			if ($apply_charge)
				$charges[]=array(
					'Charge Net Amount'=>$row['Charge Metadata'],
					'Charge Tax Amount'=>$row['Charge Metadata']*$this->data['Order Tax Rate'],
					'Charge Key'=>$row['Charge Key'],
					'Charge Description'=>$row['Charge Name']
				);



		}
		return $charges;

	}

	function get_shipping($dn_key=false) {




		if ($this->data['Order For Collection']=='Yes')
			return 0;

		if ($this->data['Order Shipping Method']=='On Demand') {
			if ($this->data['Order Shipping Net Amount']=='')
				return array('no_data',0);
			else
				return array($this->data['Order Shipping Net Amount'],0);
		}


		$sql=sprintf("select `Shipping Key`,`Shipping Metadata`,`Shipping Price Method` from `Shipping Dimension` where  `Shipping Destination Type`='Country' and `Shipping Destination Code`=%s    "
			,prepare_mysql($this->data['Order Ship To Country Code'])
			,$this->id);
		//  print_r($this->data);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$shipping=$this->get_shipping_from_method($row['Shipping Price Method'],$row['Shipping Metadata'],$dn_key);

			if (is_numeric($shipping)) {


				return array($shipping,$row['Shipping Key']);

			}
		}


		return 'no_data';


	}





	function get_shipping_from_method($type,$metadata,$dn_key=false) {
		switch ($type) {
		case('Step Order Items Gross Amount'):
			return $this->get_shipping_Step_Order_Items_Gross_Amount($metadata,$dn_key);
			break;
		}

	}


	function get_shipping_Step_Order_Items_Gross_Amount($metadata,$dn_key=false) {

		if ($dn_key) {
			$sql=sprintf("select sum( `Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)  ) as amount from `Order Transaction Fact` where `Order Key`=%d and `Delivery Note Key`=%d and `Order Quantity`!=0",
				$this->id,
				$dn_key
			);
			//print $sql;
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$amount=$row['amount'];
			} else {
				$amount=0;
			}
		} else {
			$amount=$this->data['Order Items Gross Amount'];
		}

		if ($amount==0) {

			return 0;

		}
		$data=preg_split('/\;/',$metadata);

		foreach ($data as $item) {

			list($min,$max,$value)=preg_split('/\,/',$item);
			//print "$min,$max,$value\n";
			if ($min=='') {
				if ($amount<$max)
					return $value;
			}
			elseif ($max=='') {
				if ($amount>=$min)
					return $value;
			}
			elseif ($amount<$max and $amount>=$min) {
				return $value;

			}


		}
		return 'no_data';

	}


	function update_transaction_discount_percentage($otf_key,$percentage) {
		$sql=sprintf('select `Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from  `Order Transaction Fact`  where `Order Transaction Fact Key`=%d ',
			$otf_key
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$amount=$row['Order Transaction Gross Amount']*$percentage/100;
			return $this->update_transaction_discount_amount($otf_key,$amount);
		}else {
			$this->error=true;
			$this->msg='otf not found';
		}

	}

	function update_transaction_discount_amount($otf_key,$amount,$deal_key=0) {



		if (!$deal_key) {
			$deal_info='';
		}

		$sql=sprintf('select `Product XHTML Short Description`,`Order Quantity`,`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from  `Order Transaction Fact` OTF left join `Product Dimension` P on  (P.`Product ID`=OTF.`Product ID`) where `Order Transaction Fact Key`=%d ',
			$otf_key
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {



			if ($amount==$row['Order Transaction Total Discount Amount'] or $row['Order Transaction Gross Amount']==0) {
				$this->msg='Nothing to Change';
					$return_data= array(
					'updated'=>true,
					'otf_key'=>$otf_key,
					'description'=>$row['Product XHTML Short Description'].' <span class="deal_info">'.$deal_info.'</span>',
					'discount_percentage'=>percentage($amount,$row['Order Transaction Gross Amount'],$fixed=1,$error_txt='NA',$psign=''),
					'to_charge'=>money($row['Order Transaction Gross Amount']-
					$amount,$this->data['Order Currency']),
					'qty'=>$row['Order Quantity'],
					'bonus qty'=>0
				);
				//print_r($return_data);
				return $return_data;
			}
			$sql=sprintf("delete from `Order Transaction Deal Bridge` where `Order Transaction Fact Key` =%d",$otf_key);
			mysql_query($sql);

			$this->data['Order Transaction Total Discount Amount']=$amount;
			$sql=sprintf('update `Order Transaction Fact` OTF set  `Order Transaction Total Discount Amount`=%f where `Order Transaction Fact Key`=%d ',
				$amount,
				$otf_key
			);
			mysql_query($sql);
			//print "$sql\n";
			$this->update_item_totals_from_order_transactions();
			$this->update_no_normal_totals('save');

			$this->update_totals_from_order_transactions();
$deal_info='';
			if ($amount>0  ) {
				$deal_info=percentage($amount,$row['Order Transaction Gross Amount']).' Off';



				$sql=sprintf("insert into `Order Transaction Deal Bridge` values (%d,%d,%d,%d,%s,%f,%f,0)",
					$row['Order Transaction Fact Key'],
					$this->id,
					$row['Product Key'],
					$deal_key,

					prepare_mysql($deal_info,false),
					$amount,
					($amount/$row['Order Transaction Gross Amount'])
				);
				mysql_query($sql);
				$this->updated=true;
}
				return array(
					'updated'=>true,
					'otf_key'=>$otf_key,
					'description'=>$row['Product XHTML Short Description'].' <span class="deal_info">'.$deal_info.'</span>',
					'discount_percentage'=>percentage($amount,$row['Order Transaction Gross Amount'],$fixed=1,$error_txt='NA',$psign=''),
					'to_charge'=>money($row['Order Transaction Gross Amount']-$amount,$this->data['Order Currency']),
					'qty'=>$row['Order Quantity'],
					'bonus qty'=>0
				);


			


		}
		else {
			$this->error=true;
			$this->msg='otf not found';
		}


	}

	function update_order_discounts() {
		$sql=sprintf("select D.`Deal Key`,`Deal Description` from `Order Deal Bridge` B left join `Deal Dimension` D on (B.`Deal Key`=D.`Deal Key`) where `Order Key`=%d",
			$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_array($res)) {

			//  $deal=new Deal($row['Deal Key']);


			$sql=sprintf("select * from `Deal Metadata Dimension` where `Deal Metadata Allowance Target` in ('Order','Shipping','Charge') and `Deal Key`=%d",
				$row['Deal Key']);
			$res2=mysql_query($sql);
			while ($row2=mysql_fetch_array($res2)) {
				switch ($row2['Deal Metadata Allowance Target']) {
				case 'Order':
					switch ($row2['Deal Metadata Allowance Type']) {
					case 'Credit':

						list($net,$tax_code)=preg_split('/;/',$row2['Deal Metadata Allowance']);
						$_tax_category=new TaxCategory('code',$tax_code);
						$tax=$_tax_category->data['Tax Category Rate']*$net;
						$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Transaction Outstandind Net Amount Balance`,`Transaction Outstandind Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)  values (%d,%s,%s,%d,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
							$this->id,
							prepare_mysql($this->data['Order Date']),
							prepare_mysql('Deal'),
							$row2['Deal Metadata Key'],
							prepare_mysql($row['Deal Description']),
							$net,
							prepare_mysql($tax_code),
							$tax,
							$net,
							$tax,
							prepare_mysql($this->data['Order Currency']),
							$this->data['Order Currency Exchange'],
							prepare_mysql($this->data['Order Original Metadata'])
						);

						// print ("$sql\n");
						mysql_query($sql);


						$sql=sprintf("update `Order Deal Bridge` set `Used`='Yes' where `Deal Key`=%d ",$row['Deal Key']);
						mysql_query($sql);
						break;
					default:

						break;
					}


					break;
				default:

					break;
				}

			}





		}

	}

	function update_discounts() {
		$this->allowance=array('Family Percentage Off'=>array());
		$this->deals=array('Family'=>array('Deal'=>false,'Terms'=>false,'Deal Multiplicity'=>0,'Terms Multiplicity'=>0));

		$sql=sprintf("select `Product Code`,`Order Transaction Fact Key`,`Product Key`,`Order Transaction Gross Amount`,`Order Quantity` from `Order Transaction Fact` where `Order Key`=%d",
			$this->id);
		$res_lines=mysql_query($sql);
		while ($row_lines=mysql_fetch_array($res_lines)) {
			//     print "\n".$row_lines['Product Code']."\n";


			//  $line_number=$row_lines['Order Transaction Fact Key'];
			$product_key=$row_lines['Product Key'];
			$qty=$row_lines['Order Quantity'];
			$amount=$row_lines['Order Transaction Gross Amount'];

			//  print "$line_number,$product_key,$qty,$amount\n";

			$product=new Product('key',$product_key);
			$family_key=$product->data['Product Family Key'];

			$deals_metadata=array();
			$discounts=0;

			$sql=sprintf("select * from `Deal Metadata Dimension` DM left join `Deal Dimension` D on  (D.`Deal Key`=DM.`Deal Key`)  left join `Order Deal Bridge` B on (B.`Deal Key`=DM.`Deal Key`)  where `Deal Metadata Trigger`='Family' and `Deal Metadata Trigger Key` =%d  and `Order Key`=%d and `Deal Metadata Status`='Active' and `Deal Terms Object`='Order' ",
				$family_key,
				$this->id
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$deals_metadata[$row['Deal Metadata Key']]=$row;
			}

			//print"++++++\n";
			//   print_r($deals_metadata);

			foreach ($deals_metadata as $deal_metadata ) {

				$terms_ok=true;



				$this->deals['Family']['Deal']=true;
				$this->deals['Family']['Deal Multiplicity'];


				$this->deals['Family']['Terms']=true;
				$this->deals['Family']['Terms Multiplicity']++;





				switch ($deal_metadata['Deal Metadata Allowance Type']) {
				case('Percentage Off'):
					switch ($deal_metadata['Deal Metadata Allowance Target']) {
					case('Family'):

						if ($terms_ok) {

							$percentage=$deal_metadata['Deal Metadata Allowance'];
							if (isset($this->allowance['Family Percentage Off'][$family_key])) {
								if ($this->allowance['Family Percentage Off'][$family_key]['Percentage Off']<$percentage)
									$this->allowance['Family Percentage Off'][$family_key]['Percentage Off']=$percentage;
							} else
								$this->allowance['Family Percentage Off'][$family_key]=array(
									'Family Key'=>$family_key,
									'Percentage Off'=>$percentage,
									'Deal Metadata Key'=>$deal_metadata['Deal Metadata Key'],
									'Deal Info'=>$deal_metadata['Deal Metadata Name'].' '.$deal_metadata['Deal Metadata Allowance Description']
								);
						}

						break;
					}


					break;
				}
			}

			//          print_r($this->allowance['Family Percentage Off']);
			//print "------------------\n";

			$deals_metadata=array();
			$sql=sprintf("select * from `Deal Metadata Dimension`    where `Deal Metadata Trigger`='Family' and `Deal Metadata Trigger Key` =%d and `Deal Metadata Status`='Active'  "
				,$family_key
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$deals_metadata[$row['Deal Metadata Key']]=$row;
			}


			// print $sql;



			foreach ($deals_metadata as $deal_metadata ) {

				$terms_ok=false;
				switch ($deal_metadata['Deal Metadata Terms Type']) {
				case('Family Quantity Ordered'):


					$this->deals['Family']['Deal']=true;
					$this->deals['Family']['Deal Multiplicity'];

					$qty_family=0;
					$sql=sprintf('select sum(`Order Quantity`) as qty  from `Order Transaction Fact` OTF where `Order Key`=%d and `Product Family Key`=%d '
						,$this->id
						,$family_key
					);

					$res2=mysql_query($sql);
					if ($deal_metadata2=mysql_fetch_array($res2)) {
						$qty_family=$deal_metadata2['qty'];
					}
					if ($qty_family>=$deal_metadata['Deal Metadata Terms']) {
						$terms_ok=true;;
						$this->deals['Family']['Terms']=true;
					} $this->deals['Family']['Terms Multiplicity']++;


					break;
				}


				switch ($deal_metadata['Deal Metadata Allowance Type']) {
				case('Percentage Off'):
					switch ($deal_metadata['Deal Metadata Allowance Target']) {
					case('Family'):
						if ($terms_ok) {

							$percentage=$deal_metadata['Deal Metadata Allowance'];
							if (isset($this->allowance['Family Percentage Off'][$family_key])) {
								if ($this->allowance['Family Percentage Off'][$family_key]['Percentage Off']<$percentage)
									$this->allowance['Family Percentage Off'][$family_key]['Percentage Off']=$percentage;
							} else
								$this->allowance['Family Percentage Off'][$family_key]=array(
									'Family Key'=>$family_key,
									'Percentage Off'=>$percentage,
									'Deal Metadata Key'=>$deal_metadata['Deal Metadata Key'],
									'Deal Info'=>$deal_metadata['Deal Metadata Name'].' '.$deal_metadata['Deal Metadata Allowance Description']
								);
						}

						break;
					}


					break;
				}
			}


			//if ($row_lines['Product Code']=='ABPX-06') {
			//    exit;
			//  }
			//    print_r($this->allowance['Family Percentage Off']);
		}



		// Applying allowances

		$sql=sprintf('update `Order Transaction Fact`  set  `Order Transaction Total Discount Amount`=0 where `Order Key`=%d  '
			,$this->id
		);
		mysql_query($sql);
		$sql=sprintf("delete from `Order Transaction Deal Bridge` where `Order Key` =%d and `Deal Metadata Key`!=0  ",$this->id);
		mysql_query($sql);





		foreach ($this->allowance['Family Percentage Off'] as $allowance_data) {


			//$sql=sprintf('update `Order Transaction Fact` OTF  set  `Order Transaction Total Discount Amount`=`Order Transaction Gross Amount`*%f where `Order Key`=%d and `Product Family Key`=%d '
			// ,$allowance_data['Percentage Off']
			//  ,$this->id
			//  ,$allowance_data['Family Key']
			// );
			// mysql_query($sql);

			$sql=sprintf('select OTF.`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount` from  `Order Transaction Fact` OTF  where `Order Key`=%d and `Product Family Key`=%d '
				,$this->id
				,$allowance_data['Family Key']
			);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$sql=sprintf("insert into `Order Transaction Deal Bridge` values (%d,%d,%d,%d,%s,%f,%f,0)"
					,$row['Order Transaction Fact Key']
					,$this->id

					,$row['Product Key']
					,$allowance_data['Deal Metadata Key']

					,prepare_mysql($allowance_data['Deal Info'])
					,$row['Order Transaction Gross Amount']*$allowance_data['Percentage Off']
					,prepare_mysql($allowance_data['Percentage Off'])
				);
				mysql_query($sql);
				//print "$sql\n";
			}
		}

		$sql=sprintf("select * from `Order Transaction Deal Bridge` where `Order Key`=%d  ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			if ( $row['Fraction Discount']>0  ) {
				$sql=sprintf('update `Order Transaction Fact` OTF  set  `Order Transaction Total Discount Amount`=`Order Transaction Gross Amount`*%f where `Order Transaction Fact Key`=%d '
					,$row['Fraction Discount']
					,$row['Order Transaction Fact Key']
				);
				mysql_query($sql);
			}
		}



	}

	function get_discounted_products() {
		$sql=sprintf('select  `Product Key` from   `Order Transaction Deal Bridge`   where `Order Key`=%d  group by `Product Key` '
			,$this->id
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		$disconted_products=array();
		while ($row=mysql_fetch_array($res)) {
			$disconted_products[$row['Product Key']]=$row['Product Key'];
		}
		return $disconted_products;

	}

	function update_deal_bridge_from_assets_deals() {


		$sql=sprintf("select B.`Deal Key` from  `Order Deal Bridge` B  left join `Deal Dimension` D on (D.`Deal Key`=B.`Deal Key`) where `Deal Terms Object` in ('Department','Family','Product') and `Order Key`=%d",$this->id);
		// exit("$sql\n");
		$res=mysql_query($sql);
		$deal_keys=array();
		while ($row=mysql_fetch_assoc($res)) {
			$deal_keys[]=$row['Deal Key'];
		}
		if (count($deal_keys)) {
			$sql=sprintf("delete from `Order Deal Bridge` where `Deal Key` in (%s)   ",join(',',$deal_keys));
			mysql_query($sql);
		}

		$sql=sprintf("select distinct `Deal Key` from  `Order Transaction Deal Bridge` B  left join `Deal Metadata Dimension` D on (D.`Deal Metadata Key`=B.`Deal Metadata Key`)  where`Order Key`=%d and `Deal Key`!=0",
			$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$sql=sprintf("insert into `Order Deal Bridge` values(%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'",$this->id,$row['Deal Key']);
			mysql_query($sql);
		}







	}

	function update_deals_usage() {

		$sql=sprintf("select `Deal Key` from  `Order Deal Bridge` where `Order Key`=%d",$this->id);
		// exit("$sql\n");
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$deal=new Deal($row['Deal Key']);
			$deal->update_usage();
		}

	}


	function update_shipping_method($value) {

		$sql=sprintf("update `Order Dimension` set `Order Shipping Method`=%s where `Order Key`=%d"
			,prepare_mysql($value)
			,$this->id
		);
		mysql_query($sql);

		$this->data['Order Shipping Method']=$value;

	}


	function update_order_is_for_collection($value) {

		if ($value!='Yes')
			$value='No';

		$old_value=$this->data['Order For Collection'];
		if ($old_value!=$value) {

			if ($value=='Yes') {
				$store=new Store($this->data['Order Store Key']);
				$collection_address=new Address($store->data['Store Collection Address Key']);
				if ($collection_address->id) {
					$store_country_code=$collection_address->data['Address Country 2 Alpha Code'];
					$store_main_country_code=$collection_address->data['Address Country Code'];
					$store_main_city_code=$collection_address->data['Address Town'];
					$store_main_region_code=$collection_address->data['Address World Region'];
					$store_main_postal_code=$collection_address->data['Address Postal Code'];

				} else {
					$store_country_code='XX';
					$store_main_country_code='UNK';
					$store_main_city_code='';
					$store_main_region_code='UNKN';
					$store_main_postal_code='';


				}
				$sql=sprintf("update `Order Dimension` set `Order For Collection`='Yes' ,`Order Ship To Country Code`='', `Order Main Country 2 Alpha Code`=%s, `Order Main World Region Code`=%s, `Order Main Country Code`=%s,`Order Main Town`=%s,`Order Main Postal Code`=%s,`Order XHTML Ship Tos`='',`Order Ship To Keys`='' where `Order Key`=%d"
					,prepare_mysql($store_country_code)
					,prepare_mysql($store_main_region_code)
					,prepare_mysql($store_main_country_code)
					,prepare_mysql($store_main_city_code)
					,prepare_mysql($store_main_postal_code)
					,$this->id
				);
				mysql_query($sql);

			} else {
				$customer=new Customer($this->data['Order Customer Key']);

				$ship_to= $customer->set_current_ship_to('return object');





				$sql=sprintf("update `Order Dimension` set `Order For Collection`='No' ,`Order Ship To Country Code`=%s,`Order XHTML Ship Tos`=%s,`Order Ship To Keys`=%s  ,`Order Ship To World Region Code`=%s,`Order Ship To Town`=%s,`Order Ship To Postal Code`=%s      where `Order Key`=%d"
					,prepare_mysql($ship_to->data['Ship To Country Code'])

					,prepare_mysql($ship_to->data['Ship To XHTML Address'])
					,prepare_mysql($ship_to->id)
					,prepare_mysql($ship_to->get('World Region Code'))
					,prepare_mysql($ship_to->data['Ship To Town'])
					,prepare_mysql($ship_to->data['Ship To Postal Code'])
					,$this->id
				);
				mysql_query($sql);
			}
			$this->get_data('id',$this->id);
			$this->new_value=$value;
			$this->updated=true;

		} else {
			$this->msg=_('Nothing to change');

		}


	}

	function update_ship_to($ship_to_key=false) {

		if (!$ship_to_key) {
			$customer=new Customer($this->data['Order Customer Key']);
			$ship_to= $customer->set_current_ship_to('return object');
		} else {
			//TODO
			$ship_to=new Ship_To($ship_to_key);
		}
		// $this->data ['Order Ship To Key To Deliver'],
		//prepare_mysql ( $this->data ['Order XHTML Ship Tos'] ,false),
		//prepare_mysql ( $this->data ['Order Ship To Keys'],false),

		// prepare_mysql ( $this->data ['Order Ship To Country Code'],false ),



		$sql=sprintf("update `Order Dimension` set `Order For Collection`='No' ,`Order Ship To Key To Deliver`=%d,  `Order Ship To Country Code`=%s,`Order XHTML Ship Tos`=%s,`Order Ship To Keys`=%s  ,`Order Ship To World Region Code`=%s,`Order Ship To Town`=%s,`Order Ship To Postal Code`=%s   where `Order Key`=%d"
			,$ship_to->id
			,prepare_mysql($ship_to->data['Ship To Country Code'])
			,prepare_mysql($ship_to->data['Ship To XHTML Address'])
			,prepare_mysql($ship_to->id)
			,prepare_mysql($ship_to->get('World Region Code'))
			,prepare_mysql($ship_to->data['Ship To Town'])
			,prepare_mysql($ship_to->data['Ship To Postal Code'])

			,$this->id
		);
		mysql_query($sql);
		//print $sql;
		if (mysql_affected_rows()>0) {
			$this->get_data('id',$this->id);
			$this->updated=true;
			$this->new_value=$ship_to->data['Ship To XHTML Address'];
		} else {
			$this->msg=_('Nothing to change');
		}

	}


	function add_ship_to($ship_to_key) {
		$order_ship_to_keys=preg_split('/\s*\,\s*/',$this->data ['Order Ship To Keys']);
		if (!in_array($ship_to_key,$order_ship_to_keys)) {
			$ship_to=new Ship_To($ship_to_key);
			if ($this->data ['Order Ship To Keys']=='') {
				$this->data ['Order Ship To Keys']=$ship_to_key;
				$this->data ['Order XHTML Ship Tos']='<div>'.$ship_to->display('xhtml').'</div>';
				$this->data ['Order Ship To Country Code']=$ship_to->data['Ship To Country Code'];
				$this->data ['Order Ship To World Region Code']=$ship_to->get('World Region Code');
				$this->data ['Order Ship To Town']=$ship_to->data['Ship To Town'];
				$this->data ['Order Ship To Postal  Code']=$ship_to->data['Ship To Postal Code'];
			} else {
				$this->data ['Order Ship To Keys'].=','.$ship_to_key;
				$this->data ['Order XHTML Ship Tos'].='<div>'.$ship_to->display('xhtml').'</div>';
			}
		}
	}

	function update_full_search() {

		$first_full_search=$this->data['Order Public ID'].' '.$this->data['Order Customer Name'].' '.strftime("%d %b %B %Y",strtotime($this->data['Order Date']));
		$second_full_search=strip_tags(preg_replace('/\<br\/\>/',' ',$this->data['Order XHTML Ship Tos'])).' '.$this->data['Order Customer Contact Name'];
		$img='';

		$amount='';
		if ($this->data['Order Current Payment State']=='Waiting Payment' or $this->data['Order Current Payment State']=='Partially Paid') {
			$amount=' '.money($this->data['Order Total Amount'],$this->data['Order Currency']);
		}
		elseif ($this->data['Order Current Payment State']=='Paid' or $this->data['Order Current Payment State']=='Payment Refunded') {
			$amount=' '.money($this->data['Order Balance Total Amount'],$this->data['Order Currency']);
		}

		$show_description=$this->data['Order Customer Name'].' ('.strftime("%e %b %Y", strtotime($this->data['Order Date'])).') '.$this->data['Order Current XHTML State'].$amount;

		$description1='<b><a href="order.php?id='.$this->id.'">'.$this->data['Order Public ID'].'</a></b>';
		$description='<table ><tr style="border:none;"><td  class="col1"'.$description1.'</td><td class="col2">'.$show_description.'</td></tr></table>';


		$sql=sprintf("insert into `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`) values  (%s,'Order',%d,%s,%s,%s,%s,%s) on duplicate key
                     update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s"
			,$this->data['Order Store Key']
			,$this->id
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search,false)
			,prepare_mysql($this->data['Order Public ID'],false)
			,prepare_mysql($description,false)
			,prepare_mysql($img,false)
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search,false)
			,prepare_mysql($this->data['Order Public ID'],false)
			,prepare_mysql($description,false)


			,prepare_mysql($img,false)
		);
		mysql_query($sql);



		$sql=sprintf("insert into `Search Full Text Dimension` values  (%s,'Order',%d,%s,%s) on duplicate key update `First Search Full Text`=%s ,`Second Search Full Text`=%s "
			,$this->data['Order Store Key']
			,$this->id
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search)
			,prepare_mysql($first_full_search)
			,prepare_mysql($second_full_search)
		);
		mysql_query($sql);

	}


	public function prepare_file_as($number) {

		$number=strtolower($number);
		if (preg_match("/^\d+/",$number,$match)) {
			$part_number=$match[0];
			$file_as=preg_replace('/^\d+/',sprintf("%012d",$part_number),$number);

		}
		elseif (preg_match("/\d+$/",$number,$match)) {
			$part_number=$match[0];
			$file_as=preg_replace('/\d+$/',sprintf("%012d",$part_number),$number);

		}
		else {
			$file_as=$number;
		}

		return $file_as;
	}



	function get_number_post_order_transactions() {
		$sql=sprintf("select count(*) as num from `Order Post Transaction Dimension` where `Order Key`=%d  ",$this->id);
		$res=mysql_query($sql);
		$number=0;
		if ($row=mysql_fetch_assoc($res)) {
			$number=$row['num'];
		}
		return $number;
	}

	function get_number_items() {
		$sql=sprintf("select count(*) as num from `Order Transaction Fact` where `Order Key`=%d  ",$this->id);
		$res=mysql_query($sql);
		$number=0;
		if ($row=mysql_fetch_assoc($res)) {
			$number=$row['num'];
		}
		return $number;
	}


	function update_number_items() {
		$this->data['Order Number Items']=$this->get_number_items();
		$sql=sprintf("update `Order Dimension` set `Order Number Items`=%d where `Order Key`=%d",
			$this->data['Order Number Items'],
			$this->id
		);
		mysql_query($sql);
	}


	function get_post_transactions_in_process_data() {
		$data=array(
			'Refund'=>array('Distinct_Products'=>0,'Amount'=>0,'Formated_Amount'=>money(0,$this->data['Order Currency'])),
			'Credit'=>array('Distinct_Products'=>0,'Amount'=>0,'Formated_Amount'=>money(0,$this->data['Order Currency'])),
			'Resend'=>array('Distinct_Products'=>0,'Market_Value'=>0,'Formated_Market_Value'=>money(0,$this->data['Order Currency']))

		);
		$sql=sprintf("select `Invoice Currency Code`, sum(`Quantity`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity`) as value, count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where `Invoice Quantity`>0 and POT.`Order Key`=%d and   `Operation`='Refund'",
			$this->id

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Refund']['Distinct_Products']=$row['num'];
			$data['Refund']['Amount']=$row['value'];
			$data['Refund']['Formated_Amount']=money($row['value'],$row['Invoice Currency Code']);
		}

		$sql=sprintf("select `Invoice Currency Code`, sum(`Quantity`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity`) as value, count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where `Invoice Quantity`>0 and POT.`Order Key`=%d and   `Operation`='Credit'",
			$this->id

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Credit']['Distinct_Products']=$row['num'];
			$data['Credit']['Amount']=$row['value'];
			$data['Credit']['Formated_Amount']=money($row['value'],$row['Invoice Currency Code']);
		}

		$sql=sprintf("select  `Product Currency`,sum(`Quantity`*`Product History Price`) as value,  count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) left join `Product History DImension` PH on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  where `Operation`='Resend' and POT.`Order Key`=%d ",
			$this->id
		);





		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Resend']['Distinct_Products']=$row['num'];
			$data['Resend']['Market_Value']=$row['value'];
			$data['Resend']['Formated_Market_Value']=money($row['value'],$row['Product Currency']);

		}


		return $data;

	}



	function cancel_post_transactions_in_process() {
		$this->deleted_post_transactions=0;
		$sql=sprintf("delete from `Order Post Transaction Dimension` where `Order Key`=%d ",
			$this->id
		);
		mysql_query($sql);
		$this->deleted_post_transactions=mysql_affected_rows();



	}

	function create_post_transaction_in_process($otf_key,$key,$values) {

		if (!preg_match('/^(Quantity|Operation|Reason|To Be Returned)$/',$key)) {
			$this->error=true;
			return;
		}
		$this->deleted_post_transaction=false;
		$this->update_post_transaction=false;
		$this->created_post_transaction=false;
		$this->updated=false;
		$sql=sprintf('select * from `Order Post Transaction Dimension` where `Order Transaction Fact Key`=%d',$otf_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['Order Key']!=$this->id) {
				$this->error=true;
				return;
			}

			if ($key=='Quantity' and $values[$key]<=0) {
				$sql=sprintf("delete from `Order Post Transaction Dimension` where `Order Post Transaction Key`=%d ",
					$row['Order Post Transaction Key']
				);
				mysql_query($sql);
				if (mysql_affected_rows()>0) {
					$this->update_post_transaction=true;
					$this->updated=true;

					$opt_key=$row['Order Post Transaction Key'];
					$this->deleted_post_transaction=true;
				}
			} else {


				$sql=sprintf("update `Order Post Transaction Dimension` set `%s`=%s where `Order Post Transaction Key`=%d ",
					$key,
					prepare_mysql($values[$key]),
					$row['Order Post Transaction Key']
				);
				mysql_query($sql);
				if (mysql_affected_rows()>0) {



					$this->update_post_transaction=true;
					$this->updated=true;
					$opt_key=$row['Order Post Transaction Key'];



				}
			}

		} else {
			$sql=sprintf("insert into `Order Post Transaction Dimension` (`Order Transaction Fact Key`,`Order Key`,`Quantity`,`Operation`,`Reason`,`To Be Returned`) values (%d,%d,%f,%s,%s,%s)",
				$otf_key,
				$this->id,
				$values['Quantity'],
				prepare_mysql($values['Operation']),
				prepare_mysql($values['Reason']),
				prepare_mysql($values['To Be Returned'])

			);
			mysql_query($sql);
			if (mysql_affected_rows()>0) {
				$this->created_post_transaction=true;
				$this->updated=true;
				$opt_key=mysql_insert_id();
			}

		}
		$transaction_data=array();
		if ($this->created_post_transaction or $this->update_post_transaction) {

			$sql=sprintf('select `Operation`,`Reason`,`Quantity`,`To Be Returned` from `Order Post Transaction Dimension` where `Order Transaction Fact Key`=%d',$otf_key);
			$res2=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res2)) {
				$transaction_data['Quantity']=$row['Quantity'];
				$transaction_data['Operation']=$row['Operation'];
				$transaction_data['Reason']=$row['Reason'];
				$transaction_data['To Be Returned']=$row['To Be Returned'];
			}


			$transaction_data['Order Post Transaction Key']=$opt_key;
		}
		if ($this->deleted_post_transaction) {
			$transaction_data['Quantity']='';
			$transaction_data['Operation']='';
			$transaction_data['Reason']='';
			$transaction_data['To Be Returned']='';
		}
		return $transaction_data;

	}


	function add_post_order_transactions($data) {
		$otf_key=array();
		$sql=sprintf("select `Order Post Transaction Key`,OTF.`Product Key`,`Product Gross Weight`,`Quantity`,`Product Units Per Case` from `Order Post Transaction Dimension` POT  left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) left join `Product History Dimension`  PH on (PH.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)   where POT.`Order Key`=%d  and `State`='In Process' ",
			$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$order_key=$this->id;
			$order_date=date('Y-m-d H:i:s');
			$order_public_id=$this->data['Order Public ID'];

			$bonus_quantity=0;
			$sql = sprintf( "insert into `Order Transaction Fact` (`Order Date`,`Order Key`,`Order Public ID`,`Delivery Note Key`,`Delivery Note ID`,`Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Estimated Weight`,`Order Last Updated Date`,
                             `Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
                             `Current Dispatching State`,`Current Payment State`,`Customer Key`,`Delivery Note Quantity`,`Ship To Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Metadata`,`Store Key`,`Units Per Case`,`Customer Message`)
                             values (%s,%s,%s,%d,%s,%f,%s,%f,%s,%s,%s,  %s,
                             %d,%d,%s,%d,%d,
                             %s,%s,%d,%s,%s,%.2f,%.2f,%s,%s,%f,'') ",
				prepare_mysql($order_date),
				prepare_mysql($order_key),
				prepare_mysql($order_public_id),

				0,
				prepare_mysql(''),

				$bonus_quantity,
				prepare_mysql('Resend'),
				$data['Order Tax Rate'],
				prepare_mysql ($data['Order Tax Code']),
				prepare_mysql ( $this->data['Order Currency'] ),
				$row['Product Gross Weight']*$row['Quantity'],

				prepare_mysql($order_date),
				$product->id,
				$product->data['Product ID'],
				prepare_mysql($product->data['Product Code']),
				$product->data['Product Family Key'],
				$product->data['Product Main Department Key'],

				prepare_mysql ( 'In Process' ),
				prepare_mysql ( $data ['Current Payment State'] ),
				prepare_mysql ( $this->data['Order Customer Key' ] ),

				$row['Quantity'],
				prepare_mysql ( $data['Ship To Key'] ),
				$data['Gross'],
				0,
				prepare_mysql ( $data ['Metadata'] ,false),
				prepare_mysql ( $this->data['Order Store Key'] ),
				$row['Product Units Per Case']

			);



			if (! mysql_query( $sql ))
				exit ( "$sql can not update xx orphan transaction\n" );
			$otf_key=mysql_insert_id();

			$sql=sprintf("update  `Order Post Transaction Dimension` set `Order Post Transaction Fact Key`=%d where `Order Post Transaction Key`=%d   ",$otf_key,$row['Order Post Transaction Key']);
			mysql_query( $sql );
		}

		if (array_key_exists('Supplier Metadata', $data)) {

			$sql = sprintf( "update`Order Transaction Fact` set  `Supplier Metadata`=%s  where `Order Transaction Fact Key`=%d ",
				prepare_mysql($data['Supplier Metadata']),
				$otf_key

			);
			//        print "$sql\n";
			mysql_query($sql);
		}

		return array('otf_key'=>$otf_key);

	}

	function get_notes() {
		$notes=$this->data['Order Customer Sevices Note'];

		return $notes;

	}




}





?>
